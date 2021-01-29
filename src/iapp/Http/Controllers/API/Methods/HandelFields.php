<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;


trait HandelFields
{
    public function handelFields($except, $fields, $requestArray) {
        return handel_fields($except, $fields, $requestArray);
    }
}
