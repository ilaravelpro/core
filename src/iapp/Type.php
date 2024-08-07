<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 8:27 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Validation\Rule;

class Type extends Model
{
    protected $guarded = ['id'];

    public static $s_prefix = "IT";
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    public static $find_names = ['name'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        parent::saving(function ($event) {
            if ($event->parent && $event->parent->grandpa) {
                $event->grandpa_id = $event->parent->grandpa_id;
            }elseif ($event->parent && $event->parent->parent) {
                $event->grandpa_id = $event->parent->parent_id;
            }
            if ($event->parent && !$event->grandpa_id) {
                $event->grandpa_id = $event->parent_id;
            }
        });
        static::deleted(function (self $event) {
            $event->kids()->delete();
        });
    }

    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }

    public function grandpa()
    {
        return $this->belongsTo(imodal('Type'), 'grandpa_id');
    }

    public function parent()
    {
        return $this->belongsTo(imodal('Type'), 'parent_id');
    }

    public function kids()
    {
        return $this->hasMany(imodal('Type'), 'parent_id');
    }

    protected function getTextTitleAttribute()
    {
        return $this->parent && $this->parent_id != $this->grandpa_id ? implode('->',[$this->parent->text_title , $this->title]) : $this->title;
    }

    public function rules(Request $request, $action, $arg1 = null)
    {
        $arg1 = is_string($arg1) ? $this::findBySerial($arg1) : $arg1;
        $rules = [];
        switch ($action) {
            case 'store':
                $rules = ["creator_id" => "required|exists:users,id"];
            case 'update':
                $rules = array_merge($rules, [
                    'parent_id' => "nullable|exists:types,id",
                    'title' => "required|string",
                    'name' => ['required','slug'],
                    'description' => "nullable|string",
                    'status' => 'nullable|in:' . join(',', iconfig('status.types', iconfig('status.global'))),
                ]);
                $rules['name'][] = Rule::unique('types')->where(function ($query) use ($request, $arg1) {
                    if ($arg1)
                        $query->where('id', '!=', $arg1->id);
                    $query->where('name', $request->name? : $arg1->name);
                });
                break;
        }
        return $rules;
    }

    public static function findByName($name)
    {
        return static::where('name', strtolower($name))->first();
    }
}
