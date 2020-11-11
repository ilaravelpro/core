<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Illuminate\Database\Eloquent\Model as Eloquent;

class _Post extends Eloquent
{
    use Modal;

    protected $guarded = [
        'id'
    ];

    public static $s_prefix = 'BP';
    public static $s_start = 729000000;
    public static $s_end = 21869999999;

    protected $hidden = [];
    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();
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
}
