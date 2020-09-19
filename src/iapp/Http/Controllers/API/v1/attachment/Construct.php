<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Attachment;


trait Construct
{
    public function __construct(\Illuminate\Http\Request $request)
    {
        parent::__construct($request);
        $this->model = imodal('Post');
    }
}
