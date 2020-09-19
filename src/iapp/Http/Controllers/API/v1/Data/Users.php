<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\DB;

trait Users
{
    public function users(Request $request, $role)
    {
        $modal = imodal('User');
        if (!isset($request->client) && in_array(auth()->user()->role , ipreference('admins'))){
            $users = DB::table('users')
                ->select(DB::raw('id as value'), DB::raw('name as text,family'))
                ->where('users.role', '=', $role)
                ->get()
                ->toArray();
        }else
            $users = DB::table('users')
                ->select(DB::raw('id as value'), DB::raw('name as text,family'))
                ->join('client_user', 'users.id', '=', 'client_user.user_id')
                ->where('client_user.client_id', '=', \App\Client::id($request->client))
                ->where('users.role', '=', $role)
                ->get()
                ->toArray();
        $users = array_map(function ($val) use ($modal) {
            $val->value = $modal::serial($val->value);
            $val->text = $val->text . ' ' . $val->family;
            unset($val->family);
            return $val;
        }, $users);;
        return ['data' => $users];
    }
}
