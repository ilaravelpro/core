<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Update
{
    public function update(Request $request, $parent, $scope)
    {
        $parent = $this->parentModel::findBySerial($parent);
        $scope = $this->model::where(['role_id' => $parent->id, 'scope' => $scope])->first();
        return $this->_update($request, $parent, $scope);
    }
}
