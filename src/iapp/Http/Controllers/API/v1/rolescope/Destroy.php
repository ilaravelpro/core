<?php

namespace iLaravel\Core\IApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $parent, $record)
    {
        $parent = $this->parentModel::findBySerial($parent);
        $record = $this->model::where(['role_id' => $parent->id, 'scope' => $record])->first();
        return $this->_destroy($request, $parent, $record);
    }
}
