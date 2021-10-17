<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\CodeTag;
use App\Models\User;
use App\Services\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webpatser\Uuid\Uuid;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try
        {
            $credentials = $request->all();
            $token = auth()->attempt($credentials);
            if(!$token)
            {
                return Response::error('Email hoặc mật khẩu không chính xác', 401);
            }
            return Response::data($token,1);
        }
        catch (\Exception $exception)
        {
            Log::error('AuthController - Login: '. $exception->getMessage());
            return Response::error('Có lỗi xảy ra, xin vui lòng thử lại sau', 500);
        }
    }


    public function register(RegisterRequest $request)
    {
        try
        {
            $data = $request->all();

            $codeTag = CodeTag::where('code_tag', $data['code_tag'])->first();
            if(!$codeTag) {
                return Response::error('Mã thẻ không tồn tại', 400);
            }

            $user = new User();
            $user->id = (string)Uuid::generate(4);
            $user->fill($data);
            $user->template_code = 2;
            $user->password = bcrypt($data['password']);
            $user->save();

            $codeTag->delete();

            $token = auth()->login($user);
            return Response::data($token, 1);
        }
        catch (\Exception $exception)
        {
            Log::error('AuthController - register: '. $exception->getMessage());
            return Response::error('Có lỗi xảy ra, vui lòng thử lại sau', 500);
        }
    }

    public function me()
    {
        return Response::data(auth()->user(), 1);
    }

    public function logout()
    {
        return Response::data(auth()->logout());
    }
}
