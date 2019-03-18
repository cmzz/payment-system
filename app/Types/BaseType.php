<?php
/**
 * Project: points-core-system
 * File: BaseType.php
 *
 * Created by PhpStorm.
 * User: wuzhuo
 * Email: zhuowu@hk01.com
 * Date: 18-9-3
 * Time: 下午6:16
 */

namespace App\Types;

use App\Exceptions\InvalidArgumentException;

abstract class BaseType
{
    abstract public static function names() : array;

    /**
     * @param int $v
     * @return string
     */
    public static function getName(int $v) : string
    {
        try {
            return static::names()[$v];
        } catch (\ErrorException $e) {
            throw new InvalidArgumentException(sprintf('Invaid: %s const value %s', __CLASS__, $v));
        }
    }

    /**
     * @param string $v
     * @return int
     */
    public static function toEnumValue(string $v) : int
    {
        if ($v === null) {
            throw new InvalidArgumentException('parameter is invaild');
        }

        $constants = static::names();

        $key = array_search($v, $constants);

        if ($key === false) {
            throw new InvalidArgumentException('parameter is invaild');
        }

        return $key;
    }
}
