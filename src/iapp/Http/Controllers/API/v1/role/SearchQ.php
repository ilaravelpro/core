<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Role;


trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        $model->where(function ($query) use ($q) {
            $query->where('guards.name', 'LIKE', "%$q%")
                ->orWhere('guards.group', 'LIKE', "%$q%");
        });
    }
}
