<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\UserScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $parent, $scope)
    {
        $scope = $this->model::where(['user_id' => $parent, 'scope' => $scope])->first();
        return $this->_destroy($request, $this->parentModel::findBySerial($parent), $scope);
    }
}
