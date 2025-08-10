<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 8:22 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;
use iLaravel\Core\iApp\Attachment;
use iLaravel\Core\iApp\Database\Eloquent\Builder;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

trait Modal
{
    use \iLaravel\Core\iApp\Methods\Serial,
        \iLaravel\Core\iApp\Methods\Data;

    use \iLaravel\Core\iApp\Traits\SaveAttachments;

    public $set_creator = true;
    public $set_slug = false;
    public $check_content = false;

    public $eagerLoad = [];
    public $pivots = [];

    public static function init()
    {
        return new static;
    }

    public static function statusList()
    {
        return static::init()->_statuses();
    }

    public function _statuses()
    {
        return iconfig('status.' . $this->getTable()) ?: iconfig('status.global');
    }


    public static function typeList()
    {
        return static::init()->_types();
    }

    public function _types()
    {
        return iconfig('types.' . $this->getTable()) ?: iconfig('types.global');
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

    public function validationAttributes($request, $action, $arg, ...$args)
    {
        $attributes = [];
        $requestAll = $request->all();

        foreach ($requestAll as $index => $item) {
            if (is_array($item)) {
                $this->validationAttributesCheck($index, $this->validationAttributesTrans($index), $item, $attributes);
            }else $attributes[$index] = $this->validationAttributesTrans($index);
        }

        foreach (static::getTableColumns() as $column) {
            $attributes[$column] = $this->validationAttributesTrans($column);
        }
        return $attributes;
    }

    function validationAttributesTrans($name)
    {
        return is_int($name) ? ($name + 1) : _t(ucfirst(str_replace(['-','_', '.', '/'], ' ', $name)));
    }
    function validationAttributesCheck($pindex, $title, $data, &$attributes)
    {
        foreach ($data as $index => $item) {
            if (is_array($item)) {
                $this->validationAttributesCheck("{$pindex}.{$index}", implode('->' ,[$title, $this->validationAttributesTrans($index)]), $item, $attributes);
            }else $attributes["{$pindex}.{$index}"] = implode('->' ,[$title, $this->validationAttributesTrans($index)]);
        }
    }

    public static function getValidationReplacers($request, $action, $item = null, ...$args)
    {
        $model = new static();
        return method_exists($model, 'validationReplacers') ? $model->validationReplacers($request, $action, $item, ...$args) : [];
    }

    public function newQuery($connection = null, $table = null)
    {
        if ($connection)
            $this->setConnection($connection);
        if ($table)
            $this->setTable($table);
        return parent::newQuery(); // TODO: Change the autogenerated stub
    }

    public static function query($connection = null, $table = null)
    {
        return (new static)->newQuery($connection, $table);
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

    public static function reviewFiles($content,  $path = '', $check_file = true) {
        $list = [];
        foreach ($content as $index => $item) {
            if ((is_array($item) || is_object($item)) && stripos($index, '_file') === false && !($item instanceof UploadedFile)) {
                $list = array_merge($list, static::reviewFiles((array)$item, "{$path}{$index}.", $check_file));
            }else {
                if (stripos($index, '_file') !== false/* && ($check_file ? ($item instanceof UploadedFile) : true)*/) {
                    $list[] = $path . str_replace('_file', '', $index);
                }
            }
        }
        return $list;
    }

    public function saveFile($name, $request, $event = null) {
        $attachment = $this->saveAttachment($name."_file", $request);
        $post = imodal('Attachment');
        try {
            if ($attachment){
                if ($this->{$name."_id"} && $post::find($this->{$name."_id"}))
                    $post::find($this->{$name."_id"})->delete();
                $this->{$name."_id"} = $attachment->id;
            }elseif ($request->{$name."_delete"}){
                if ($this->{$name."_id"} && $post::find($this->{$name."_id"}))
                    $post::find($this->{$name."_id"})->delete();
                $this->{$name."_id"} = null;
            }
        }catch (\Throwable $exception) {}
        unset($this->{$name."_file"});
    }

    public function saveFileInContent(&$content, $parent_name, $name, $request, $event = null) {
        $old_content = $this->getOriginal($parent_name);
        $old_id = _get_value($content, "{$name}_id");
        $post = imodal('Attachment');
        if (\request()->file("{$parent_name}.{$name}_file") instanceof UploadedFile) {
            $attachment = $this->saveAttachment("{$parent_name}.{$name}_file", $request);
            if ($attachment){
                $oldValue = _get_value($content, "{$name}_id");
                if ($oldValue && ($oldAttachment = ($post::find(is_int($oldValue) ? $oldValue : $post::id($oldValue)))))
                    $oldAttachment->delete();
                _unset_key((array)$content, "{$name}");
                $content = _set_value($content, "{$name}_file", null);
                $content = _set_value($content, "{$name}_id", $attachment->id);
                $content = _set_value($content, "{$name}_url", $attachment->attachments->where('mode', 'original')->first()->url);
            }
            return $attachment;
        }else {
            $content = _unset_key($content, "{$name}");
            $content = _unset_key($content, "{$name}_url");
            if ($old_id) {
                if ($old_id && ($oldAttachment = ($post::find(is_int($old_id) ? $old_id : $post::id($old_id))))) {
                    $content = _set_value($content, "{$name}_file", null);
                    $content = _set_value($content, "{$name}_id", $oldAttachment->id);
                    $content = _set_value($content, "{$name}_url", $oldAttachment->attachments->where('mode', 'original')->first()->url);
                    return $oldAttachment;
                }
            } else {
                $oldValue = _get_value($old_content, "{$name}_id");
                if ($oldValue && ($oldAttachment = ($post::find(is_int($oldValue) ? $oldValue : $post::id($oldValue)))))
                    $oldAttachment->delete();
                return $oldAttachment;
            }

        }
        return false;
    }

    public function saveAttachment($name, $request, $sizes = ["52","75", "150" ,"300" , "600" ,"900"]) {
        try {
            $fileattachment = $request->file($name);
            if($fileattachment){
                \request()->files->remove($name);
                \request()->request->remove($name);
                $is_image = preg_match(' /(?:image)/', $fileattachment->getClientMimeType());
                $is_svg = in_array($fileattachment->extension(), ['svg']);
                $file = imodal('File');
                $attachment = $file::upload($request, $name);
                if ($attachment) {
                    if (!$is_svg && $is_image){
                        foreach ($sizes as $size)
                            $file::imageSize($attachment, $size);
                    }
                    return $attachment;
                }
            }
        }catch (\Throwable $exception) {
        }
        return false;
    }

    public function saveFiles($names, $request, $event = null) {
        if ($names && count($names))
            foreach ($names as $name)
                $this->saveFile($name, $request, $event);
    }

    public function saveFilesInContent(&$content, $parent_name, $names, $request, $event = null) {
        foreach ($names as $name)
            $this->saveFileInContent($content, $parent_name, $name, $request, $event);
    }

    public function getFile($key)
    {
        if (!$this->{$key.'_id'}) return $this->{$key.'_id'};
        $file = imodal('File');
        return $file::where('post_id', $this->{$key.'_id'})->get()->keyBy('mode');
    }


    protected function getContentAttribute($value)
    {
        return is_json($value) ? json_decode($value, true) : $value;
    }

    public static function iBoot() {
        parent::creating(function (self $event) {
            if ($event->set_creator && $event->hasTableColumn('creator_id') && auth()->check())
                $event->creator_id = auth()->id();
            if ($event->set_slug && $event->hasTableColumn('slug')) {
                $slug = to_slug($event->{$event->set_slug});
                $slugs = static::where('slug', 'like', "$slug%")->get();
                $event->slug = $slug . ($slugs->count() ? ("-". $slugs->count()) : '');
            }
        });
        parent::updating(function (self $event) {
            if ($event->set_slug && $event->hasTableColumn('slug')) {
                $slug = to_slug($event->{$event->set_slug});
                $slugs = static::where('slug', 'like', "$slug%")->where('id', '!=' , $event->id)->get();
                $event->slug = $slug . ($slugs->count() ? ("-". $slugs->count()) : '');
            }
        });
        parent::saving(function (self $event){
            if ($event->check_content && $event->{$event->check_content} && $event->hasTableColumn($event->check_content) && (is_array($event->content) || is_object($event->content))) {
                $content = $event->getAttributeValue($event->check_content);
                $event->saveFilesInContent( $content, $event->check_content, static::reviewFiles($event->getOriginal($event->check_content)?:$content, '', false), Request::createFrom(\request()));
                $event->{$event->check_content} = json_encode($content);
            }
        });
        /*parent::saved(function (self $event) {
             $event->resetCacheTable();
        });*/
        parent::deleting(function (self $event){
            if (method_exists($event, 'attachments'))
                foreach ($event->attachments as $attachment) $attachment->delete();
            if ($event->files && count($event->files)) {
                $attachment = imodal('Attachment');
                foreach ($event->files as  $name) {
                    if ($event->{$name."_id"} && ($file = $attachment::find($event->{$name."_id"}))) $file->delete();
                }
            }
            //  $event->resetCacheTable();
        });
    }

    public static $find_names = [];

    public static function findByAny($value){
        if (!count(static::$find_names)) return false;
        return static::where('id', static::id($value))->orWhere(function ($q) use($value) {
            foreach (array_values(static::$find_names) as $index => $name) {
                $q->{$index > 0 ? "orWhere" : "where"}($name, $value);
            }
        })->first();
    }

    public static function getByAny($values){
        if (!count(static::$find_names)) return false;
        $ids = remove_empty(array_map(function ($v) {
            return static::id($v);
        }, $values));
        return static::where('id', $ids)->orWhere(function ($q) use($values) {
            foreach (array_values(static::$find_names) as $index => $name) {
                $q->{$index > 0 ? "orWhere" : "where"}(function ($q) use($name, $values) {
                    foreach ($values as $index => $value) {
                        $q->{$index > 0 ? "orWhere" : "where"}($name, $value);
                    }
                });
            }
        })->get();
    }

    public static function getTableName(){
        $table = null;
        try {
            $table = with(new static())->getTable();
        }catch (\Throwable $exception) {}
        return $table;
    }

    public static function getTableNameDot(){
        $table = static::getTableName();
        $table .= $table ? "." : "";
        return $table;
    }

    public static function findQ($value){
        $table = static::getTableNameDot();
        return static::where(function ($q) use($value, $table) {
            foreach (static::getTableColumns() as $index => $column) {
                if (in_array($column, ['id', 'parent_id']))
                    $q->where($table.$column, $value);
                else
                    $q->orWhere($table.$column, 'LIKE', "%$value%");
            }
        })->first();
    }

    public static function getQ($value){
        $values = is_array($value) ? $value : [$value];
        $ids = remove_empty(array_map(function ($v) {
            return static::id($v);
        }, $values));
        return static::where('id', $ids)->orWhere(function ($q) use($values) {
            foreach (static::getTableColumns() as $index => $name) {
                $q->{$index > 0 ? "orWhere" : "where"}(function ($q) use($name, $values) {
                    foreach ($values as $index => $value) {
                        $q->{$index > 0 ? "orWhere" : "where"}($name, 'LIKE', "%$value%");
                    }
                });
            }
        })->get();
    }

    public function __call($method, $parameters)
    {
        if ($this->files && count($this->files)) {
            foreach ($this->files as  $name) {
                if ($method == ("get".ucfirst($name)."Attribute")) {
                    return $this->getFile($name);
                }
            }
        }
        return parent::__call($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function save_pivots($data = null)
    {
        if ($data?:$this->pivots) {
            foreach ($data?:$this->pivots as $modal => $pivots) {
                foreach ($pivots as $pivot => $options) {
                    if (method_exists($this, $pivot)) {
                        if (isset($options["request"]) && is_array($options["request"])) {
                            foreach ($options["request"] as $section => $groups) {
                                foreach ($groups as $group) {
                                    $items = [];
                                    foreach ($request->{"{$group}s"} ?: [] as $item)
                                        if (isset($options["callback"]) && is_callable($options["callback"]))
                                            $options["callback"]($this, $items, $item, imodal($modal)::id($item), $section, $group, $pivot, $modal, $options);
                                        else $items[imodal($modal)::id($item)] = [$section => $group];
                                    $this->$pivot()->sync($items);
                                }
                            }
                        }else {
                            $items = [];
                            foreach ($request->$pivot ?: [] as $item)
                                if (isset($options["callback"]) && is_callable($options["callback"]))
                                    $options["callback"]($this, $items, $item, imodal($modal)::id($item), $pivot, $modal, $options);
                                else $items[imodal($modal)::id($item)] = [$section => $group];
                            $this->$pivot()->sync($items);
                        }
                    }
                }
            }
        }
    }

    public function additionalUpdate($request = null, $additional = null, $parent = null){
        $additional = $additional ? :$this->getAdditional();
        $this->saveFiles($this->files, $request);
        $this->save_attachments($additional, $request);
        $this->save();
        $this->save_pivots();
    }

    public function resetCacheTable() {
        return static::_resetCacheTable($this->getTable());
    }

    public static function _resetCacheTable($table = null) {
        $table = $table?:static::getTableName();
        if (strpos($table, 'log') === false) {
            $keys = Redis::keys("ilaravel:db:{$table}:*");
            foreach ($keys as $key)
                Redis::del($key);
        }
        return false;
    }
}
