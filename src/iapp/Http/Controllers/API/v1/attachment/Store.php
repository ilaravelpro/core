<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Attachment;

use iLaravel\Core\IApp\File;
use iLaravel\Core\IApp\Post;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Store
{
    public function store(Request $request)
    {
        \DB::beginTransaction();
        $request->request->remove('file');
        $post_record = $this->_store($request);
        $slug = "/attachments/$post_record->serial". ($post_record->title ? '_'. $post_record->title : '');
        $post_record->resource->slug = $slug;
        $post_record->resource->url = asset($slug);
        $post_record->resource->save();
        $file = File::move($post_record->resource, $request->file('file'));
        \DB::commit();
        return new $this->resourceClass(Post::find($post_record->id));
    }
}
