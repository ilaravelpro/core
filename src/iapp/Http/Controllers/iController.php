<?php

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
        Methods\Call;
}
