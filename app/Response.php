<?php

declare(strict_types=1);

namespace App;

class Response
{
    public static function success()
    {
        return static::response(0, 'success', [], []);
    }

    public static function successData($data)
    {
        return static::response(0, 'success', $data, []);
    }

    public static function successDataExtra($data, $extra)
    {
        return static::response(0, 'success', $data, $extra);
    }

    public static function error($code, $message)
    {
        return static::response($code, $message, [], []);
    }

    public static function response($code, $message, $data, $extra)
    {
        if (!$data)
            $data = (object)[];

        if (!$extra) {
            $extra = (object)[];
        }

        $respData = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'extra' => $extra
        ];

        return response()->json($respData);
    }
}
