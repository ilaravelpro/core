<?php

namespace iLaravel\Core\iApp\Modals;

trait Modal
{
    use Serial;

    public $datetime = [
        'global' => 'Y-m-d H:i:s',
    ];

    public $files = [

    ];

    public static function statusList(){
        $status = iconfig('status.'.(new self())->getTable());
        return $status ? : iconfig('status.global');
    }

    public static function typeList(){
        $types = iconfig('types.'.(new self())->getTable());
        return $types ? : iconfig('types.global');
    }

    public function getCreatedAtAttribute($value)
    {
        return format_datetime($value, $this->datetime, 'created_at');
    }

    public function getUpdatedAtAttribute($value)
    {
        return format_datetime($value, $this->datetime, 'updated_at');
    }
}
