<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 9:37 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class iResponse
{
    public function handle($request, Closure $next)
    {

        $response = $next($request);
        if ($request->ajax() && $response instanceof \Illuminate\Http\RedirectResponse) {
            $result = [
                'is_ok' => true,
                'redirect' => $response->getTargetUrl(),
                'direct' => true
            ];
            result_message($result, 'redirect');
            $response = response()->json(
                $result,
                200
            );
        } else if ($response instanceof JsonResponse || ($request->segment(1) == 'api' && $response->exception)) {
            if ($response->exception) {
                return $response;
            } else {
                $result = json_decode($response->content(), true);
                $result = array_merge([
                    'is_ok' => true
                ], $result);
                if (isset($result['links'])) {
                    $links = $result['links'];
                    unset($result['links']);
                    $result['links'] = $links;
                }
                if (isset($result['meta'])) {
                    $meta = $result['meta'];
                    unset($result['meta']);
                    $result['meta'] = $meta;
                }
                if ($request->route()->getAction('controller')) {
                    $controller = $request->route()->getController();
                    if (isset($controller->statusMessage)) {
                        result_message($result, $controller->statusMessage);
                    } else {
                        result_message($result, ':)');
                    }
                }
                $response = response()->json(
                    $result,
                    $response->status(),
                    $response->headers->all()
                );
            }
        }
        return $response;
    }

    public function terminate($request, $response)
    {
        /*\iLaravel\iLogs\iApp\iLog::create([
            'user_id' => auth()->id(),
            'endpoint' => $request->url(),
            'method' => $request->method(),
            'request' => $request->toArray(),
            'header_request' => $request->headers->all(),
            'response' => $response->getContent(),
            'header_response' => $response->headers->all(),
            'execute_time' => microtime(true) - LARAVEL_START
        ]);*/
    }
}
