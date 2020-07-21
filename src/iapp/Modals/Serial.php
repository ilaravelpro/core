<?php
namespace iLaravel\Core\IApp\Modals;

use iLaravel\Core\Vendor\iSerial as Engine;

trait Serial
{
	public function getSerialAttribute()
	{
		return $this->id !== null ? self::serial($this->id) : null;
	}

	public static function serial($id)
	{
		return self::$s_prefix . Engine::encode($id + self::$s_start);
	}

	public static function id($serial = null)
	{
		if (substr($serial, 0, strlen(self::$s_prefix)) != self::$s_prefix) {
			return false;
		}
		return Engine::decode(substr($serial, strlen(self::$s_prefix))) - self::$s_start;
	}

	public static function serialCheck($serial)
	{
		$id = self::id($serial);
		if(!$id || ($id + self::$s_start) < self::$s_start || self::$s_end < ($id + self::$s_start)) return false;
		return true;
    }

    public function getSerialTextAttribute()
    {
		return self::$s_prefix . '-'. Engine::encode($this->id + self::$s_start);
    }

    public function resolveRouteBinding($value)
    {
        $value = self::id($value);
        return parent::resolveRouteBinding($value);
    }

    public static function findBySerial($serial)
    {
        return static::serialCheck($serial) ? static::find(static::id($serial)) : null;
    }
}
