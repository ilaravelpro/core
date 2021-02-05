<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/29/21, 12:59 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;


trait HandelFields
{
    public function handelFields($except, $fields, $requestArray) {
        return handel_fields($except, $fields, $requestArray);
    }
}
