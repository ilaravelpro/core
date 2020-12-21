<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/6/20, 12:03 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\Methods;


trait SearchQ
{
    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        if ($id = $this->model::id($q)) $q = $id;
        if (!$id && isset($this->parentModel) && $this->parentModel && $id = $this->parentModel::id($q)) $q = $id;
        $model->where(function ($query) use ($q) {
            $query->where('users.title', 'LIKE', "%$q%")
                ->orWhere('users.slug', 'LIKE', "%$q%")
                ->orWhere('users.description', 'LIKE', "%$q%");
        });
    }
}
