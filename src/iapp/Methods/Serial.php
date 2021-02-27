<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 11:25 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Methods;

use iLaravel\Core\Vendor\iSerial as Engine;

trait Serial
{

    public function getSerialAttribute()
    {
        return static::decode_id($this->id);
    }

    public static function serial($id)
    {
        return static::decode_id($id);
    }

    public static function id($serial = null)
    {
        return static::encode_id($serial);
    }

    public static function decode_id($id)
    {
        return static::$s_prefix . Engine::encode($id + static::$s_start);
    }

    public static function encode_id($serial)
    {
        $serial = strtoupper($serial);
        if (substr($serial, 0, strlen(static::$s_prefix)) != static::$s_prefix) {
            return false;
        }
        return Engine::decode(substr($serial, strlen(static::$s_prefix))) - static::$s_start;
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
        $id = static::encode_id($serial);
        if (!$id || ($id + static::$s_start) < static::$s_start || static::$s_end < ($id + static::$s_start)) return false;
        return true;
    }

    public static function idCheck($id)
    {
        if (!$id || ($id + static::$s_start) < static::$s_start || static::$s_end < ($id + static::$s_start)) return false;
        return true;
    }

    public function getSerialTextAttribute()
    {
        return static::$s_prefix . '-' . Engine::encode($this->id + static::$s_start);
    }

    public function resolveRouteBinding($value, $filed = null)
    {
        $value = static::encode_id($value);
        return parent::resolveRouteBinding($value);
    }

    public static function findBySerial($serial)
    {
        return static::serialCheck($serial) ? static::find(static::encode_id($serial)) : null;
    }
}
