<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 1:19 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Illuminate\Support\Facades\DB;

trait Modal
{
    use \iLaravel\Core\iApp\Methods\Serial,
        \iLaravel\Core\iApp\Methods\Data;

    public $datetime = [
        'global' => 'Y-m-d H:i:s',
    ];

    public $files = [

    ];

    public static function statusList()
    {
        $status = iconfig('status.' . (new self())->getTable());
        return $status ?: iconfig('status.global');
    }

    public static function typeList()
    {
        $types = iconfig('types.' . (new self())->getTable());
        return $types ?: iconfig('types.global');
    }

    public function getCreatedAtAttribute($value)
    {
        return format_datetime($value, $this->datetime, 'created_at');
    }

    public function getUpdatedAtAttribute($value)
    {
        return format_datetime($value, $this->datetime, 'updated_at');
    }

    public static function resetRecordsId()
    {
        DB::statement(DB::raw('ALTER TABLE ' . (new self())->getTable() . ' AUTO_INCREMENT=0'));
        DB::statement(DB::raw('set @reset:=0'));
        return DB::statement(DB::raw('UPDATE ' . (new self())->getTable() . ' SET id = @reset:= @reset + 1'));
    }

    public static function getTableColumns()
    {
        dd((new self)->getTable());
        return \DB::getSchemaBuilder()->getColumnListing((new self)->getTable());
    }
}
