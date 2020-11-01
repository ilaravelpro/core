<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use iLaravel\Core\Vendor\iSerial as Engine;

trait Serial
{

    public function getSerialAttribute()
    {
        return self::decode_id($this->id);
    }

    public static function serial($id)
    {
        return self::decode_id($id);
    }

    public static function id($serial = null)
    {
        return self::encode_id($serial);
    }

    public static function decode_id($id)
    {
        return self::$s_prefix . Engine::encode($id + self::$s_start);
    }

    public static function encode_id($serial)
    {
        $serial = strtoupper($serial);
        if (substr($serial, 0, strlen(self::$s_prefix)) != self::$s_prefix) {
            return false;
        }
        return Engine::decode(substr($serial, strlen(self::$s_prefix))) - self::$s_start;
    }

    public static function rangeId($serial)
    {
        $ziro = substr(Engine::$ALPHABET, 0, 1);
        $biggest = substr(Engine::$ALPHABET, -1, 1);
        $length = strlen(static::decode_id(1));
        try {
            $first = $length == strlen($serial) ? $serial : $serial . str_repeat($ziro, $length - strlen($serial));
            $last = $length == strlen($serial) ? $serial : substr($first, 0, $length - 1) . $biggest;
            return [
                static::encode_id($first),
                static::encode_id($last)
            ];
        } catch (\Throwable $th) {
            return [
                false,
                false
            ];
        }
    }

    public static function serialCheck($serial)
    {
        $id = self::encode_id($serial);
        if (!$id || ($id + self::$s_start) < self::$s_start || self::$s_end < ($id + self::$s_start)) return false;
        return true;
    }

    public static function idCheck($id)
    {
        if (!$id || ($id + self::$s_start) < self::$s_start || self::$s_end < ($id + self::$s_start)) return false;
        return true;
    }

    public function getSerialTextAttribute()
    {
        return self::$s_prefix . '-' . Engine::encode($this->id + self::$s_start);
    }

    public function resolveRouteBinding($value, $filed = null)
    {
        $value = self::encode_id($value);
        return parent::resolveRouteBinding($value);
    }

    public static function findBySerial($serial)
    {
        return static::serialCheck($serial) ? static::find(static::encode_id($serial)) : null;
    }
}
