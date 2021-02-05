<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/18/21, 2:10 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;

use iLaravel\Core\iApp\File;
use iLaravel\Core\iApp\Post;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Store
{
    public function store($request)
    {
        \DB::beginTransaction();
        $request->request->remove('file');
        $post_record = $this->_store(new Request($request->all()));
        $slug = "/attachments/$post_record->serial". ($post_record->title ? '_'. $post_record->title : '');
        $post_record->resource->slug = $slug;
        $post_record->resource->url = asset($slug);
        $post_record->resource->save();
        $file = File::move($post_record->resource, $request->file('file'));
        \DB::commit();
        return new $this->resourceClass(Post::find($post_record->id));
    }
}
