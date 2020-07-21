<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Attachment;


trait Construct
{
    public function __construct(\Illuminate\Http\Request $request)
    {
        parent::__construct($request);
        $this->model = imodal('Post');
    }
}
