<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 8:22 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use iLaravel\Core\iApp\Post;
use Illuminate\Support\Facades\DB;

trait Modal
{
    use \iLaravel\Core\iApp\Methods\Serial,
        \iLaravel\Core\iApp\Methods\Data;


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
        return format_datetime($value, isset($this->datetime) ? $this->datetime : [], 'created_at');
    }

    public function getUpdatedAtAttribute($value)
    {
        return format_datetime($value, isset($this->datetime) ? $this->datetime : [], 'updated_at');
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

    public static function getRules($request, $action, $item = null, ...$args) {
        $model = new static();
        return method_exists($model, 'rules') ? $model->rules($request, $action, $item, ...$args) : [];
    }

    public function getAdditional($request = null) {
        if (!$request) $request = request();
        $rules = method_exists($this, 'rules') ? $this->rules($request, 'additional', $this) : [];
        if (!count($rules)) return $request->all();
        $fields = handel_fields([], array_keys($rules), $request->all());
        $data = [];
        foreach ($fields as $value)
            if (_has_key($request->toArray(), $value))
                $data = _set_value($data, $value, _get_value($request->toArray(), $value));
        return $data;
    }

    public function saveFile($name, $request, $event = null) {
        $fileattachment = $request->file($name."_file");
        \request()->files->remove($name."_file");
        \request()->request->remove($name."_file");
        $file = imodal('File');
        $post = imodal('Attachment');
        if($fileattachment){
            $attachment = $file::upload($request, $name."_file");
            if ($attachment) {
                if ($this->{$name."_id"} && $post::find($this->{$name."_id"}))
                    $post::find($this->{$name."_id"})->delete();
                $this->{$name."_id"} = $attachment->id;
                if (preg_match(' /(?:image)/', $fileattachment->getClientMimeType()))
                    foreach (["52","75", "150" ,"300" , "600" ,"900"] as $size)
                        $file::imageSize($attachment, $size);
            }
            unset($this->{$name."_file"});
        }
    }

    public function saveFiles($names, $request, $event = null) {
        foreach ($names as $name)
            $this->saveFile($name, $request, $event);
    }

    public function getFile($key)
    {
        if (!$this->{$key.'_id'}) return $this->{$key.'_id'};
        $file = imodal('File');
        return $file::where('post_id', $this->{$key.'_id'})->get()->keyBy('mode');
    }
}
