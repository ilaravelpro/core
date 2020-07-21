<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Attachment;
use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait Fields
{
    public function fields(Request $request, $action)
    {
        $data = [
            'status' => $request->status ?: '',
            'title' => $request->title,
            'content' => $request->post('content'),
        ];
        if($action == 'store')
        {
            $data['type'] = 'attachment';
            $data['creator_id'] = auth()->id();
        }
        return $data;
    }
}
