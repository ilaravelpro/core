<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/21/21, 12:30 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Carbon\Carbon;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

use iLaravel\Core\iApp\Model;
use iLaravel\Core\iApp\PostMeta;

class _Attachment extends Model
{
    use \iLaravel\Core\iApp\Methods\Metable;

    protected $guarded = ['id'];

    public static $s_prefix = 'IF';
    public static $s_start = 729000000;
    public static $s_end = 21869999999;

    protected $hidden = [];
    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
    ];
    protected $table = 'posts';

    public $metaClass = PostMeta::class;
    public $metaTable = 'post_meta';

    public $size_max = 102400;
    public $mimes = 'jpeg,jpg,png,gif,zip,pdf';

    protected static function boot()
    {
        parent::boot();
        parent::saving(function (self $event) {
            $event->type = 'attachment';
            if (!$event->status)
                $event->status = 'publish';
            if ($event->status == 'publish' && !$event->published_at)
                $event->published_at = Carbon::now()->timestamp;

        });
        parent::deleted(function (self $event) {
            if ($event->type == 'attachment') {
                foreach ($event->attachments as $file) {
                    if (file_exists($file->slug)) {unlink($file->slug); $file->delete();}
                }
            }
        });
    }

    public function attachments()
    {
        return $this->hasMany(imodal('File'));
    }

    public function creator()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function rules(Request $request, $action, $parent = null)
    {
        $rules = [];
        switch ($action) {
            case 'store':
                $rules = ["creator_id" => "required|exists:users,id"];
            case 'update':
                $rules = array_merge($rules, [
                    'file' => "required|mimes:$this->mimes|max:$this->size_max",
                    'title' => "nullable|string",
                    'slug' => 'nullable|slug',
                    'summary' => 'nullable|string',
                    'order' => "nullable|numeric|min:0",
                    'published_at' => "nullable|date_format:Y-m-d H:i:s",
                    'status' => 'nullable|in:' . join(iconfig('status.attachments', iconfig('status.global')), ','),
                ]);
                break;
        }
        return $rules;
    }
}
