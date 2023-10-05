<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 11:25 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

use iLaravel\Core\iApp\Model;
use iLaravel\Core\iApp\PostMeta;

class _Post extends Model
{
    use \iLaravel\Core\iApp\Methods\Metable;

    protected $guarded = ['id'];

    public static $s_prefix = 'IP';
    public static $s_start = 729000000;
    public static $s_end = 21869999999;

    protected $hidden = [];
    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
    ];

    public $metaClass = PostMeta::class;
    public $metaTable = 'post_meta';

    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }

    public function rules(Request $request, $action, $parent = null)
    {
        $rules = [];
        switch ($action) {
            case 'store':
                $rules = ["creator_id" => "required|exists:users,id"];
            case 'update':
                $rules = array_merge($rules, [
                    'parent_id' => "nullable|exists:terms,id",
                    'title' => "required|string",
                    'slug' => 'nullable|slug',
                    'content' => 'nullable|string',
                    'summary' => 'nullable|string',
                    'type' => 'nullable|exists:types,name',
                    'template' => 'nullable|string',
                    'order' => "required|numeric|min:0",
                    'published_at' => "nullable|date_format:Y-m-d H:i:s",
                    'status' => 'nullable|in:' . join(',', iconfig('status.posts', iconfig('status.global'))),
                ]);
                break;
        }
        return $rules;
    }
}
