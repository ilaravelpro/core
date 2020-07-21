<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Post;


trait Filters
{
    public function filters($request, $model, $parent = null)
    {
        $filters = [
            [
                'status' => config('ilaravel.status.posts', ['awaiting', 'active', 'disable']),
            ]
        ];
        $current = [];
        if (in_array($request->status, $filters[0]['status'])) {
            $model->where('status', $request->status);
            $current['status'] = $request->status;
        }
        return [$filters, $current];
    }
}
