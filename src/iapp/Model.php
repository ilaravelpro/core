<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp;


class Model extends \Illuminate\Database\Eloquent\Model
{
    use \iLaravel\Core\iApp\Modals\Modal;

    protected $guarded = ['id',];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        parent::creating(function (self $event) {
            if ($event->hasTableColumn('creator_id') && auth()->check())
                $event->creator_id = auth()->id();
        });
    }
}
