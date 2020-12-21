<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/6/20, 12:03 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;


trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        parent::searchQ(...func_get_args());
        $q = $request->q;
        /*$model->where(function ($query) use ($q) {
            $query->where('users.name', 'LIKE', "%$q%")
                ->orWhere('users.family', 'LIKE', "%$q%")
                ->orWhere('users.role', 'LIKE', "%$q%")
                ->orWhere('users.gender', 'LIKE', "%$q%")
                ->orWhere('users.username', 'LIKE', "%$q%");
        });*/
    }
}
