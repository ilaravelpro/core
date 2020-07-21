<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;


trait Filters
{
    public function filters($request, $model, $parent = null)
    {
        $filters = [
            [
                'status' => config('ilaravel.status.attachment', ['awaiting', 'active', 'disable']),
            ]
        ];
        $current = [];
        if(in_array($request->status, $filters[0]['status']))
        {
            $model->where('status', $request->status);
            $current['status'] = $request->status;
        }
        return [$filters, $current];
    }
}
