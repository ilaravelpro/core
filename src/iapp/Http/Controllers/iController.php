<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/21/20, 10:50 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

class iController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use Methods\Variables;

    use Methods\Construct,
        Methods\ClassName,
        Methods\FindOrFail,
        Methods\SetFillable,
        Methods\Fillable,
        Methods\Fail,
        Methods\FindArgs,
        Methods\SearchQ,
        Methods\Call;
}
