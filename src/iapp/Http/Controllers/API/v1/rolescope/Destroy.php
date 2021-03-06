<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\RoleScope;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Destroy
{
    public function destroy(Request $request, $parent, $record)
    {
        $parent = $this->parentModel::findBySerial($parent);
        $record = $this->model::where(['role_id' => $parent->id, 'scope' => $record])->first();
        return $this->_destroy($request, $parent, $record);
    }
}
