<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;

use iLaravel\Core\iApp\File;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;

trait Update
{
    public function update(Request $request, $record)
    {
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $avatar = $request->file('avatar_file');
        \request()->files->remove('avatar_file');
        \request()->request->remove('avatar_file');
        $update = $this->_update($request, $record);
        if ($avatar) {
            $attachment = File::upload($request, 'avatar_file');
            if ($attachment) {
                $update->resource->avatar_id = $attachment->id;
                $update->resource->save();
                $this->statusMessage = $this->class_name() . " changed";
            }
            File::imageSize($attachment, 500);
            File::imageSize($attachment, 250);
            File::imageSize($attachment, 150);
        }
        return $update;
    }
}
