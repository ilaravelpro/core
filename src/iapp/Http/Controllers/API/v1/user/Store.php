<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;
use iLaravel\Core\iApp\File;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Store
{
    public function store(Request $request)
    {
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $store = $this->_store($request);
        $avatar = $request->file('avatar_file');
        \request()->files->remove('avatar_file');
        \request()->request->remove('avatar_file');
        if ($avatar) {
            $attachment = File::upload($request, 'avatar_file');
            if ($attachment) {
                $store->resource->avatar_id = $attachment->id;
                $store->resource->save();
                $this->statusMessage = $this->class_name() . " Saved";
            }
            File::imageSize($attachment, 500);
            File::imageSize($attachment, 250);
            File::imageSize($attachment, 150);
        }
        return $store;
    }
}
