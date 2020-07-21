<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\Auth;

use iLaravel\Core\IApp\Http\Controllers\API\Methods\Controller\Show;
use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;
use Illuminate\Auth\AuthenticationException;

trait Me
{
    public function me(Request $request)
    {
        $this->statusMessage = 'me';
        $user = $this->show($request, \Auth::user()->serial);
        $user->additional(array_merge_recursive($user->additional, [
            'additional' => [ 'token' => $request->bearerToken() ]
        ]));
        return $user;
    }
}
