<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/10/20, 12:49 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Role;


trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        dd($this->model::getTableColumns());
        $model->where(function ($query) use ($q) {
            $query->where('roles.name', 'LIKE', "%$q%")
                ->orWhere('roles.title', 'LIKE', "%$q%");
        });

    }
}
