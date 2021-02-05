<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Update
{
    public function update(Request $request, $parent, $scope)
    {
        $parent = $this->parentModel::findBySerial($parent);
        $scope = $this->model::where(['role_id' => $parent->id, 'scope' => $scope])->first();
        return $this->_update($request, $parent, $scope);
    }
}
