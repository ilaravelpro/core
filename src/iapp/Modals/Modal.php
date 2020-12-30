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
        $table = (new self())->getTable();
        DB::statement(DB::raw('ALTER TABLE ' . $table . ' CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL'));
        DB::statement(DB::raw('ALTER TABLE ' . $table . ' DROP PRIMARY KEY'));
        DB::statement(DB::raw('ALTER TABLE ' . $table . ' AUTO_INCREMENT=0'));
        DB::statement(DB::raw('set @reset:=0'));
        $check = DB::statement(DB::raw('UPDATE ' . $table . ' SET id = @reset:= @reset + 1'));
        DB::statement(DB::raw('ALTER TABLE ' . $table . ' AUTO_INCREMENT=' .(static::all()->count() + 1)));
        DB::statement(DB::raw('ALTER TABLE ' . $table . ' ADD PRIMARY KEY( `id`)'));
        DB::statement(DB::raw('ALTER TABLE ' . $table . ' CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT'));
        return $check;
    }

    public static function cleanup($table_name)
    {
        DB::statement("SET @count = 0;");
        DB::statement("UPDATE `$table_name` SET `$table_name`.`id` = @count:= @count + 1;");
        return DB::statement("ALTER TABLE `$table_name` AUTO_INCREMENT = 1;");
    }

    public static function getTableColumns()
    {
        return \DB::getSchemaBuilder()->getColumnListing(with(new static)->getTable());
    }
}
