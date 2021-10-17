<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use function Sodium\library_version_major;

class OrderController extends Controller
{
    CONST ITEM_PER_PAGE = 20;

    public function listOrder(Request $request)
    {
        $page = Arr::get($request, 'page', 1);
        $limit = Arr::get($request, 'limit', self::ITEM_PER_PAGE);
        $name = Arr::get($request, 'name', false);
        $email = Arr::get($request, 'email', false);
        $mobile = Arr::get($request, 'mobile', false);
        $address = Arr::get($request, 'address', false);
        $code_tag = Arr::get($request, 'code_tag', false);
        $payment_status = Arr::get($request, 'payment_status', false);
        $start_time = Arr::get($request, 'start_time', false);
        $end_time = Arr::get($request, 'end_time', false);

        $query = Order::query();
        if($name) {
            $query->where('name', 'like','%'.$name.'%');
        }
        if($email) {
            $query->where('email', 'like','%'.$email.'%');
        }
        if($address) {
            $query->where('address', 'like','%'.$address.'%');
        }
        if($mobile) {
            $query->where('mobile', $mobile);
        }
        if($code_tag) {
            $query->where('code_tag', $code_tag);
        }
        if($payment_status) {
            $query->where('payment_status', $payment_status);
        }
        if($start_time && $end_time) {
            $query->whereBetween('created_at', [$start_time.' 00:00:00', $end_time. '23:59:59']);
        }
        $total = $query->count();
        $query->skip(($page - 1) * $limit);
        $query->take($limit);

        $data = $query->get();

        return Response::data(['data' => $data, 'total' => $total]);
    }
}
