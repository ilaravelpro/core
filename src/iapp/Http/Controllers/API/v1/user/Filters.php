<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\v1\User;


use iLaravel\Core\Vendor\iRole\iRole;

trait Filters
{
    public function filters($request, $model, $parent = null, $operators)
    {
        $user = auth()->user();
        $types = $user->role == 'client' ?
            ['capitan', 'officer'] :
            in_array($user->role, config('bit.admins', ['admin'])) ? config('bit.types', ['admin', 'user']) : ['users'];
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
            [
                'name' => 'status',
                'title' => _t('status'),
                'type' => 'select',
                'items' => config('bit.status', ['awaiting', 'active', 'disable'])
            ],
            [
                'name' => 'role',
                'title' => _t('role'),
                'type' => 'select',
                'items' => $types
            ],
            [
                'name' => 'gender',
                'title' => _t('gender'),
                'type' => 'select',
                'items' => ['male', 'female']
            ],
            [
                'name' => 'username',
                'title' => _t('username'),
                'type' => 'text'
            ],
        ];
        $current = [];
        /*if (iRole::has('users.viewAny')) {
            if ($user->type == 'client') {
                $model->whereHas('clients', function ($query) {
                    $query->where('id', auth()->user()->clients()->pluck('id')->all());
                });
                $model->whereIn('type', ['capitan', 'officer']);
            } elseif (!in_array($user->type, config('bit.admins', ['admin']))) {
                $model->where('creator_id', $user->id);
            }
            $current['access'] = $user->type;
        }*/
        if (iRole::has('users.viewAnyByUser') && !iRole::has('users.viewAny')) {
            $model->where('creator_id', $user->id);
            $current['access'] = $user->type;
        }
        $this->requestFilter($request, $model, $parent, $filters, $operators);
        if ($request->q) {
            $this->searchQ($request, $model, $parent);
            $current['q'] = $request->q;
        }
        return [$filters, $current, $operators];
    }
}
