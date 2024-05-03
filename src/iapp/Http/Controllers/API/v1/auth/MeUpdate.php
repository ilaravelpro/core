<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/18/21, 12:11 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\Hash;
use iLaravel\Core\iApp\File;

trait MeUpdate
{
    public function me_update(Request $request)
    {
        $user = $this->model::find(auth()->id());
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $update = [
            'name' => (string)$request->name,
            'family' => (string)$request->family,
            'website' => (string)$request->website,
            'gender' => (string)$request->gender
        ];
        if (isset($request->password))
            $update['password'] = $request->password;
        /*$avatar = \request()->file('avatar_file');
        \request()->files->remove('avatar_file');
        \request()->request->remove('avatar_file');
        if ($avatar) {
            $attachment = File::upload(\request(), 'avatar_file');
            if ($attachment) {
                $update['avatar_id'] = $attachment->id;
            }
            File::imageSize($attachment, 500);
            File::imageSize($attachment, 250);
            File::imageSize($attachment, 150);
        }*/
        $this->statusMessage = [":model :action successfully.", ['model' => _t("Profile"), 'action' => _t("updated")]];
        $user->update($update);
        return new $this->resourceClass($user);
    }
}
