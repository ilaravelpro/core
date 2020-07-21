<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;


trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        $model->where(function ($query) use ($q) {
            $query->where('users.name', 'LIKE', "%$q%")
                ->orWhere('users.family', 'LIKE', "%$q%")
                ->orWhere('users.mobile', 'LIKE', "%$q%")
                ->orWhere('users.email', 'LIKE', "%$q%")
                ->orWhere('users.type', 'LIKE', "%$q%")
                ->orWhere('users.gender', 'LIKE', "%$q%")
                ->orWhere('users.username', 'LIKE', "%$q%")
                ->orWhere('users.mobile', 'LIKE', "%$q%");
        });
    }
}
