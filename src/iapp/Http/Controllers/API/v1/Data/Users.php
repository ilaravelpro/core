<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use Illuminate\Support\Facades\DB;

trait Users
{
    public function users(Request $request, $type)
    {
        $modal = imodal('User');
        if (!isset($request->client) && in_array(auth()->user()->type , ipreference('admins'))){
            $users = DB::table('users')
                ->select(DB::raw('id as value'), DB::raw('name as text,family'))
                ->where('users.role', '=', $type)
                ->get()
                ->toArray();
        }else
            $users = DB::table('users')
                ->select(DB::raw('id as value'), DB::raw('name as text,family'))
                ->join('client_user', 'users.id', '=', 'client_user.user_id')
                ->where('client_user.client_id', '=', \App\Client::id($request->client))
                ->where('users.role', '=', $type)
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
