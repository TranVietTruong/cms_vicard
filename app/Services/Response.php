<?php

namespace App\Services;

/**
 * Response Class helper
 */
class Response
{
    public static function data($data=[], $total=0, $message='Successfully', $status=200){
        $data = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'total' => $total
        ];

        return response()->json($data, $status, [], JSON_UNESCAPED_UNICODE);
    }

    public static function error($message='Forbidden', $status=403){
        return self::data([], 0, $message, $status);
    }
}
