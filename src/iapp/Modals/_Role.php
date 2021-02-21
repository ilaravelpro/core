<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 11:25 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

use iLaravel\Core\iApp\Model;

class _Role extends Model
{
    public static $s_prefix = 'IR';
    public static $s_start = 30;
    public static $s_end = 899;

    protected $guarded = [];

    protected $hidden = ['scopes'];

    protected static function boot()
    {
        parent::boot();
        parent::creating(function (self $event) {
            $event->creator_id = auth()->id();
        });
    }

    public function scopes()
    {
        return $this->hasMany(imodal('RoleScope'));
    }

    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }

    public static function admin($name = 'admin') {
        return new static([
            'id' => 0,
            'name' => $name,
            'title' => ucfirst($name),
        ]);
    }

    public function rules(Request $request, $action, $parent = null)
    {
        $rules = [];
        switch ($action) {
            case 'store':
                $rules = ["creator_id" => "required|exists:users,id"];
            case 'update':
                $rules = array_merge($rules, [
                    'title' => 'required|min:3',
                    'name' => 'required|alpha',
                    'status' => 'nullable|in:' . join(iconfig('status.roles', iconfig('status.global')), ','),
                ]);
                break;
        }
        return $rules;
    }
}
