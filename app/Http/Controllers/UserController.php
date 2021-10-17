<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Jobs\SendNotification;
use App\Mail\ForgetPassword;
use App\Models\User;
use App\Models\UserSubscriber;
use App\Services\Response;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateStep(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_code' => 'integer|max:50'
        ]);

        if($validator->fails()) {
            return Response::error($validator->getMessageBag(), 400);
        }

        $user = auth()->user();
        if($user->template_code == $request['template_code']) {
            return Response::data();
        };
        $user->template_code = $request['template_code'];
        $user->save();

        return Response::data();
    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|max:50',
            'new_password' => 'required|min:6|max:50',
        ]);

        if($validator->fails()) {
            return Response::error($validator->getMessageBag(), 400);
        }

        $user = auth()->user();
        $currentPassword = $user->password;
        $oldPassword = $request['old_password'];
        if(!Hash::check($oldPassword, $currentPassword)) {
            return Response::error('Mật khẩu cũ không chính xác');
        }

        $user->password = bcrypt($request['new_password']);
        $user->save();
        return Response::data();
    }

    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'message' => 'required|max:200',
            'url' => 'max:255'
        ]);

        if($validator->fails()) {
            return Response::error($validator->getMessageBag(), 400);
        }

        $user = auth()->user();
        $userSubscriber = UserSubscriber::where('user_id', $user->id)->first();
        if(!$userSubscriber) {
            return Response::error('Bạn chưa có người đăng ký nhận thông báo');
        }
        if(!count($userSubscriber->subscriber)) {
            return Response::error('Bạn chưa có người đăng ký nhận thông báo');
        }

        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'url' => Arr::get($request, 'url', null)
        ];

        $subscriber = $userSubscriber->subscriber;

        SendNotification::dispatch($data, $subscriber);

        return Response::data();
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns|max:50',
        ]);

        if($validator->fails()) {
            return Response::error('Email không hợp lệ', 400);
        }

        $user = User::where('email', $request->email)->first();
        if(!$user) {
            return Response::error('Email không tồn tại');
        }
        $token = Crypt::encrypt($user->id);

        SendEmail::dispatch($user, $token);

        return Response::data();
    }

    public function checkToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return Response::error($validator->getMessageBag()->first(), 400);
        }

        try {
            $decrypted = Crypt::decrypt($request->token);
        } catch (DecryptException $e) {
            return Response::error();
        }

        $user = User::where('id', $decrypted)->where('status', 1)->first();
        if(!$user) {
            return Response::error('Không tìm thấy user', 404);
        }
        if(!$user->forget_password_at) {
            return Response::error();
        }

        $minus = Carbon::now('Asia/Ho_Chi_Minh')->diffInMinutes($user->forget_password_at);
        if($minus > 15) {
            return Response::error('Hết hạn thời gian đặt lại mật khẩu', 400);
        }

        return Response::data($request->token);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:255',
            'password' => 'required|min:6|max:50'
        ]);

        if($validator->fails()) {
            return Response::error($validator->getMessageBag()->first(), 400);
        }

        try {
            $decrypted = Crypt::decrypt($request->token);
        } catch (DecryptException $e) {
            return Response::error();
        }

        $user = User::where('id', $decrypted)->where('status', 1)->first();
        if(!$user) {
            return Response::error('Không tìm thấy user', 404);
        }
        if(!$user->forget_password_at) {
            return Response::error();
        }

        $minus = Carbon::now('Asia/Ho_Chi_Minh')->diffInMinutes($user->forget_password_at);

        if($minus > 15) {
            return Response::error('Hết hạn thời gian đặt lại mật khẩu', 400);
        }

        $user->forget_password_at = null;
        $user->password = bcrypt($request->password);
        $user->save();
        return Response::data();
    }
}
