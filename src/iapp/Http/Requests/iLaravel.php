<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/6/20, 12:03 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Requests;

use iLaravel\Core\Vendor\iMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\User;
use Illuminate\Support\Str;

class iLaravel extends FormRequest
{
    public $parseRules;

    public function validationData()
    {
        $data = parent::validationData();
        if (!$this->controller()) return $data;
        if (method_exists($this->controller(), 'requestData')) {
            $this->controller()->requestData($this, $this->route()->getActionMethod(), $data, ...array_values($this->route()->parameters()));
        }
        $this->numberTypes($data);
        $this->mobileRule($data);
        //$this->serialRule($data);
        $this->replace($data);
        return $data;

    }

    public function numberTypes(&$data)
    {
        $fields = ['country', 'number'];
        foreach ($data as $key => $value) {
            if (in_array($key, $fields) && $this->has($key)) {
                $data[$key] = $this->numberial($this->$key) ?: null;
            }
        }
    }

    public function numberial($string)
    {
        return strtr($string, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }

    public function mobileRule(&$data)
    {
        foreach ($this->parseRules() as $key => $value) {
            foreach ($value as $k => $v) {
                if ($k == 'mobile' && isset($data[$key])) {
                    list($mobile, $country, $code) = iMobile::parse($data[$key]);
                    $data[$key] = $mobile ? "$code$mobile" : $data[$key];
                }
            }
        }
    }

    public function serialRule(&$data)
    {
        foreach ($this->parseRules() as $key => $value) {
            foreach ($value as $k => $v) {
                $valid = explode(':',$k);
                if ($valid[0] == 'serial' && isset($data[$key]) && $data[$key]) {
                    $model = isset($valid[1]) ? imodal($valid[1]) : imodal(ucfirst($v));
                    if (!class_exists($model)) {
                        list($table) = explode(',', $v);
                        $table = Str::camel($table);
                        $model = isset($valid[1]) ? imodal($valid[1]) : imodal(ucfirst(Str::singular($table)));
                    }
                    if (is_array($data[$key])) {
                        $data[$key] = array_unique($data[$key]);
                        foreach ($data[$key] as $dk => $dv) {
                            $data[$key][$dk] = $model::encode_id($dv);
                        }
                    } else {
                        $data[$key] = $model::encode_id($data[$key]);
                    }
                } elseif ($valid[0] == 'exists_serial' && isset($data[$key]) && $data[$key]) {
                    list($table) = explode(',', $v);
                    $table = Str::camel($table);
                    $model = isset($valid[1]) ? imodal($valid[1]) : imodal(ucfirst(Str::singular($table)));
                    if (is_array($data[$key])) {
                        $data[$key] = array_unique($data[$key]);
                        foreach ($data[$key] as $dk => $dv) {
                            $data[$key][$dk] = $model::encode_id($dv);
                        }
                    } else {
                        $data[$key] = $model::encode_id($data[$key]);
                    }
                }
            }
        }
    }

    public function controller()
    {
        if ($this->route() && $this->route()->controller) {
            return $this->route()->getController();
        }
        return false;
    }

    public function messages()
    {
        $messages = [
            'exists_serial' => __('validation.exists'),
            'serial' => __('validation.exists'),
        ];
        if ($this->controller() && method_exists($this->controller(), 'validationMessages')) {
            return array_merge($messages, $this->controller()->validationMessages($this, $this->route()->getAction('as'), ...array_values($this->route()->parameters())));
        }
        return $messages;

    }

    public function attributes()
    {
        if ($this->controller() && method_exists($this->controller(), 'validationAttributes')) {
            return $this->controller()->validationAttributes($this, $this->route()->getAction('as'), ...array_values($this->route()->parameters()));
        }
        return [];
    }

    public function authorize()
    {
        if (!$this->controller()) return true;
        $action = $this->route()->getAction('as');
        $aAaction = explode('.', $action);
        $method = last($aAaction);
        array_pop($aAaction);
        switch ($method) {
            case 'index':
                $method = 'viewAny';
                break;
            case 'show':
                $method = 'view';
                break;
            case 'create':
            case 'store':
                $method = 'create';
                break;
            case 'edit':
            case 'update':
                $method = 'update';
                break;
            case 'destroy':
                $method = 'delete';
                break;
        }
        $aAaction[] = $method;
        $action = join('.', $aAaction);
        $action = str_replace('api.', '', $action);
        $auth = true;
        if (in_array($action, array_keys(Gate::abilities()))) {
            if (!auth()->check() && in_array('auth:apiIf', $this->route()->getAction('middleware'))) {
                auth()->login(User::guest());
            }
            $args = array_values($this->route()->parameters());
            /*array_unshift($args, $action);
            array_unshift($args, $this);*/
            $auth = $this->controller()->authorize($action, $args);
            if (auth()->id() == 0) auth()->logout();
        }
        if ($auth && $this->controller() && method_exists($this->controller(), 'gate')) {
            $auth = $this->controller()->gate($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        };
        return $auth;
    }

    public function rules()
    {
        $rules = $this->getRules() ?: [];
        if (!$this->controller()) return $rules;
        if (method_exists($this->controller(), 'manipulateData')) {
            $data = $this->all();
            $this->controller()->manipulateData($this, $this->route()->getActionMethod(), $data, ...array_values($this->route()->parameters()));
            $this->replace($data);
        }
        return $rules;
    }

    public function getRules()
    {
        $rules = [];
        if (!$this->controller()) return $rules;
        if (method_exists($this->controller(), 'rules')) {
            $rules = $this->controller()->rules($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
            $this->controller()->setFillable($this->route()->getActionMethod(), array_keys($rules));
        }
        return $rules;
    }

    public function parseRules()
    {
        if (!$this->parseRules) {
            $rules = $this->getRules() ?: [];
            $parse = [];
            foreach ($rules as $key => $value) {
                $value = is_array($value) ? $value : explode('|', trim($value));
                $p_values = [];
                foreach ($value as $k => $v) {
                    if (is_string($v)) {
                        $v = explode(':', $v, 2);
                        $p_values[$v[0]] = isset($v[1]) ? $v[1] : null;
                    }
                }
                $parse[$key] = $p_values;
            }
            $this->parseRules = $parse;
        }
        return $this->parseRules;
    }

    public function isPrefix($prefix)
    {
        return $this->getPrefix() == trim($prefix, '/');
    }

    public function getPrefix()
    {
        return trim($this->route()->getAction('prefix'), '/');
    }

    public static function virtual($routeName, User $user = null, $parameters = [], $method = null, $cookies = [], $files = [], $server = [], $content = null)
    {
        list($routeName, $routeParameters) = is_array($routeName) ? $routeName : [$routeName, null];
        $route = app('routes')->getByName($routeName);
        $uri = route($routeName, $routeParameters);
        $headers = [];
        if (in_array('api', $route->gatherMiddleware())) {
            $headers['Accept'] = 'application/json';
        }
        if (in_array('auth:api', $route->gatherMiddleware())) {
            $token = $user->createToken('api');
            $headers['Authorization'] = 'Bearer ' . $token->accessToken;
        }
        $request = static::create(
            $uri,
            $method ?: current($route->methods()),
            $parameters,
            $cookies,
            $files,
            $server,
            $content
        );
        $request->headers->add($headers);
        $app = app()['Illuminate\Contracts\Http\Kernel']->handle($request);
        if (isset($token)) {
            $token->token->delete();
        }
        return $app;
    }
}
