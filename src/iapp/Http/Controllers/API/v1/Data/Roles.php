<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Roles
{
    public function roles(Request $request)
    {
        $model = imodal('Role');
        $roles = collect();
        if (auth()->user()->role == 'admin')
            $roles->add('admin');
        $roles = $roles->merge($model::all()->pluck('name'));
        return ['data' => $roles];
    }
}
