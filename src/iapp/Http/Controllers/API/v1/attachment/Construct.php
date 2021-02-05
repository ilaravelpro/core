<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
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
