<?php

declare(strict_types=1);

namespace App\Payment;


use App\Exceptions\InvalidSignatureException;

class Signature
{
    /**
     * @param $data
     * @return array
     */
    public static function trimEmptyParam($data): array
    {
        $result = [];

        foreach ($data as $k => $v) {
            if (trim((string)$v) === '')
                continue;

            $result[$k] = $v;
        }

        return $result;
    }

    /**
     * @param array $data
     * @param string $key
     * @return string
     */
    public static function make(array $data, string $key): string
    {
        $data = static::trimEmptyParam($data);

        ksort($data);
        $sign = strtoupper(md5(urldecode(http_build_query($data)).'&key='.$key));

        return $sign;
    }

    /**
     * @param array $data
     * @param string $key
     * @param string|null $sign
     * @return bool
     * @throws InvalidSignatureException
     */
    public static function verify(array $data, string $key, string $sign = null): bool
    {
        if (!$sign) {
            if (!isset($data['sign'])) {
                throw new InvalidSignatureException();
            }

            $sign = $data['sign'];
            unset($data['sign']);
        }

        if (!$sign) {
            throw new InvalidSignatureException();
        }

        $tSign = static::make($data, $key);
        if ($sign !== $tSign) {
            throw new InvalidSignatureException();
        }

        return true;
    }
}