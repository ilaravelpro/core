<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 8:22 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

use Illuminate\Support\Facades\DB;

trait Modal
{
    use \iLaravel\Core\iApp\Methods\Serial,
        \iLaravel\Core\iApp\Methods\Data;


    public $eagerLoad = [];
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
        $table = (new static())->getTable();
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

    public static function hasTableColumn(string $column)
    {
        return method_exists(static::class, 'getTable') ? \Schema::hasColumn(with(new static)->getTable(), $column) : false;
    }

    public static function getRules($request, $action, $item = null, ...$args) {
        $model = new static();
        return method_exists($model, 'rules') ? $model->rules($request, $action, $item, ...$args) : [];
    }

    public static function getValidationAttributes($request, $action, $item = null, ...$args)
    {
        $model = new static();
        return method_exists($model, 'validationAttributes') ? $model->validationAttributes($request, $action, $item, ...$args) : [];
    }

    public static function getValidationMessages($request, $action, $item = null, ...$args)
    {
        $model = new static();
        return method_exists($model, 'validationMessages') ? $model->validationMessages($request, $action, $item, ...$args) : [];
    }

    public static function getValidationReplacers($request, $action, $item = null, ...$args)
    {
        $model = new static();
        return method_exists($model, 'validationReplacers') ? $model->validationReplacers($request, $action, $item, ...$args) : [];
    }

    public function getAdditional(Request $request = null, $action = 'additional') {
        if (!$request) $request = new Request(request()->all());
        $rules = method_exists($this, 'rules') ? $this->rules($request, $action, $this) : [];
        if (!count($rules)) return [];
        $fields = handel_fields([], array_keys($rules), $request->all());
        $data = [];
        foreach ($fields as $value)
            if (_has_key($request->toArray(), $value))
                $data = _set_value($data, $value, _get_value($request->toArray(), $value));
        return $data;
    }

    public function saveFile($name, $request, $event = null) {
        $attachment = $this->saveAttachment($name."_file", $request);
        $post = imodal('Attachment');
        if ($attachment){
            if ($this->{$name."_id"} && $post::find($this->{$name."_id"}))
                $post::find($this->{$name."_id"})->delete();
            $this->{$name."_id"} = $attachment->id;
        }
        unset($this->{$name."_file"});
    }


    public function saveAttachment($name, $request, $sizes = ["52","75", "150" ,"300" , "600" ,"900"]) {
        $fileattachment = $request->file($name);
        \request()->files->remove($name);
        \request()->request->remove($name);
        $file = imodal('File');
        if($fileattachment){
            $attachment = $file::upload($request, $name);
            if ($attachment) {
                if (preg_match(' /(?:image)/', $fileattachment->getClientMimeType())){
                    foreach ($sizes as $size)
                        $file::imageSize($attachment, $size);
                }
                return $attachment;
            }
        }
        return false;
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
