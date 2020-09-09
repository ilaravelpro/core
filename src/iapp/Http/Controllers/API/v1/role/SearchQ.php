<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Role;


trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        $model->where(function ($query) use ($q) {
            $query->where('roles.name', 'LIKE', "%$q%")
                ->orWhere('roles.title', 'LIKE', "%$q%");
        });
    }
}
