<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;

class ExceptionHandler extends Handler
{
    use QueryExceptionCode;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        $args = func_get_args();
        $render = parent::render($request, $exception);
        $data = (array) $render->getData();
        $data = array_replace_recursive([
            'is_ok' => false,
            'message' => null,
            'message_text' => null,
            'referer' => $request->headers->get('referer')
        ], $data);
        result_message($data, $data['message'] ?: Response::$statusTexts[$render->getStatusCode()]);
        if(!config('app.debug'))
        {
            if ($exception instanceof ModelNotFoundException) {
                result_message($data, str_replace('App\\', '', $exception->getModel()) . ' not found');
            } elseif ($exception instanceof QueryException) {
                result_message($data, $this->QueryException(...$exception->errorInfo));
            } elseif ($render->getStatusCode() == 500 || $render->getStatusCode() == 501) {
                result_message($data, Response::$statusTexts[$render->getStatusCode()]);
            }
            unset($data['exception']);
            unset($data['file']);
            unset($data['line']);
            unset($data['trace']);
        }
        $modalLog = imodal('Log');
        if (isset($args[2]) && $args[2] instanceof $modalLog) {
            $data['log'] = $args[2]->serial;
            $args[2]->responses()->create([
                'text' => json_encode([
                    'status' => $render->getStatusCode(),
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]),
                'type' => 'exception',
                'order' => 0,
            ]);
            if ($render->getStatusCode() !== 401) $data['message_text'] .= " ({$data['log']})";
        }
        $render->setData($data);
        return $render;
    }
}
