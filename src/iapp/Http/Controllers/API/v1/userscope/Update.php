<?php

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\UserScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Update
{
    public function update(Request $request, $parent, $scope)
    {
        $scope = $this->model::where(['user_id' => $parent, 'scope' => $scope])->first();
        return $this->_update($request, $this->parentModel::findBySerial($parent), $scope);
    }
}
