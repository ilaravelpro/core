<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 5:22 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Requests;

use Carbon\Carbon;
use iLaravel\Core\iApp\Model;
use iLaravel\Core\Vendor\Validations\iPhone;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\User;
use Illuminate\Support\Str;
use Laravel\Passport\Token;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class iLaravel extends FormRequest
{
    public $parseRules;

    public function validationData()
    {
        return $this->releaseValidationData(parent::validationData());
    }

    public function releaseValidationData($data)
    {
        $data = $this->releaseData($data);
        $this->replace($data);
        if ($this->controller() && method_exists($this->controller(), 'requestData')) {
            $this->controller()->requestData($this, $this->route()->getActionMethod(), $data, ...array_values($this->route()->parameters()));
        }
        $this->numberTypes($data);
        $this->mobileRule($data);
        //$this->serialRule($data);
        $this->replace($data);
        return $data;
    }

    public function releaseData($data, $parent = null)
    {
        try {
            foreach ($data as $index => $datum) {
                try {
                    if ($index !== 'content') {
                        if (is_array($datum) && isset($datum['value']) && isset($datum['text']) && !isset($datum['type'])) {
                            $data[$index] = $datum = $datum['value'];
                        }
                        if (substr($index, 0, 3) === 'is_' || substr($index, 0, 4) === 'has_') {
                            $data[$index] = in_array($datum, ['true', 'false', '0', '1']) ? ($datum == "true" || $datum == "1") : $data[$index];
                        }else if (substr($index, -3, 3) === '_id') {
                            try {
                                if (is_string($datum))$data[$index] = $datum = (($parent ? : new ($this->controller()->model))->{str_replace('_id', '', $index)}()->getRelated()->id($datum))?:$datum;
                            }catch (\Throwable $exception) {}
                        }else if (substr($index, -5, 5) === '_date' || ($jalali = substr($index, -6, 6) === '_jdate')) {
                            $datum = str_replace('/', '-', $datum);
                            $jalali = @$jalali?: (now()->year - explode('-', $datum)[0] >= 620);
                            $format = "Y-m-d";
                            $data[str_replace('_jdate', '_date', $index)] = $jalali ?
                                \Morilog\Jalali\Jalalian::fromFormat($format, $datum)->toCarbon()->format($format)
                                : Carbon::createFromFormat($format, $datum)->format($format);
                        } else if (substr($index, -3, 3) === '_at' || ($jalali = substr($index, -4, 4) === '_jat')) {
                            if (strlen($datum)) {
                                $datum = str_replace('/', '-', $datum);
                                $jalali = @$jalali?: (now()->year - explode('-', $datum)[0] >= 620);
                                $explodeAT = explode(' ', $datum);
                                $two_value = count($explodeAT) == 2;
                                $three_value = $two_value ?  count(explode(':', $explodeAT[1])) == 3: false;
                                $format = "Y-m-d" . ($two_value ? (" H:i" . ($three_value ?  ':s': '')) : "");
                                $format2 = "Y-m-d " . ($two_value ? "H:i:s" : "01:01:01");
                                $data[str_replace('_jat', '_at', $index)] = $jalali ?
                                    \Morilog\Jalali\Jalalian::fromFormat($format, $datum)->toCarbon()->format($format2)
                                    : Carbon::createFromFormat($format, $datum)->format($format2);
                            }
                        }  else if (in_array($index, ['filter', 'filters'])) {
                            foreach (($index == "filter" ? [$datum] : $datum) as $ifindex => $item) {
                                try {
                                    $relatedModal = (new ($this->controller()->model))->{str_replace('_id', '', $item['type'])}();
                                    $relatedModal = @$relatedModal->model? :$relatedModal->getRelated();
                                    $item['cvalue'] = is_array($item['value']) ? array_map(function ($v) use($relatedModal){
                                        return $relatedModal::findBySerial($v)?:$relatedModal::findQ($v);
                                    }, $item['value']) : ($relatedModal::findBySerial($item['value'])?:$relatedModal::findQ($item['value']));
                                    if ($item['cvalue']){
                                        $item['mvalue'] = $item['cvalue'];
                                        $item['cvalue'] = $item['cvalue']->id;
                                    }
                                    $item['model'] = $relatedModal;
                                    if ($index == "filter")$data[$index] = $item;
                                    else $data[$index][$ifindex] = $item;
                                }catch (\Throwable $exception) {
                                }
                            }
                        }  else if (is_array($datum)) {
                            try {
                                $relatedModal = $parent;
                                if (is_string($index)) {
                                    $relatedModal = (new ($this->controller()->model)(['id' => 0]))->{str_replace('_id', '', $index)}();
                                    $relatedModal = @$relatedModal->model? :$relatedModal->getRelated();
                                }
                                $data[$index] = $this->releaseData($datum, $relatedModal);
                                $data[$index] = array_map(function ($v) use($relatedModal){
                                    return is_string($v) ? ($relatedModal::id($v)?:$v) : $v;
                                }, $data[$index]);
                            }catch (\Throwable $exception) {
                                $data[$index] = $this->releaseData($datum, $parent);
                            }
                        } else if (is_string($datum) || is_numeric($datum)) {
                            $data[$index] = in_array($datum, ['true', 'false']) ? $datum == "true" : $this->numberial($datum);
                        }
                    }
                }catch (\Throwable $exception) {}
            }
        }catch (\Throwable $exception) {}
        return $data;
    }

    public function numberTypes(&$data)
    {
        $fields = ['country', 'number'];
        foreach ($data as $key => $value) {
            if (in_array($key, $fields) && $this->has($key) && !is_json($this->$key) && !is_array($this->$key)) {
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
                    $mobile = iPhone::parse($data[$key]);
                    $data[$key] = $mobile && isset($mobile['full']) ? $mobile : $data[$key];
                }
            }
        }
    }

    public function serialRule(&$data)
    {
        foreach ($this->parseRules() as $key => $value) {
            foreach ($value as $k => $v) {
                $valid = explode(':', $k);
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
        if ($this->route()) {
            if ($this->route()->controller) {
                return $this->route()->getController();
            } elseif (!_has_token() && $this->route()->getAction('controller')) {
                try {
                    $controller = explode('@', $this->route()->getAction('controller'))[0];
                    return new $controller($this);
                } catch (\Throwable $e) {}
            }
        }
        return false;
    }

    public function withValidator(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $validator->addCustomAttributes($this->attributes());
        $validator->addReplacers($this->replacers());
    }

    public function replacers()
    {
        if ($this->controller() && method_exists($this->controller(), 'validationReplacers')) {
            return $this->controller()->validationReplacers($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        } elseif ($this->controller() && $this->controller()->model && method_exists($this->controller()->model, 'validationReplacers')) {
            return $this->controller()->model::getValidationReplacers($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        }
        return [];
    }

    public function messages()
    {
        $messages = [
            'exists_serial' => __('validation.exists'),
            'serial' => __('validation.exists'),
        ];
        if ($this->controller() && method_exists($this->controller(), 'validationMessages')) {
            $messages = array_merge($messages, $this->controller()->validationMessages($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters())));
        } elseif ($this->controller() && $this->controller()->model && method_exists($this->controller()->model, 'validationMessages')) {
            $messages = array_merge($messages, $this->controller()->model::getValidationMessages($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters())));
        }
        return $messages;

    }

    public function attributes()
    {
        if (!$this->controller()) return [];
        if ($this->controller() && method_exists($this->controller(), 'validationAttributes')) {
            return $this->controller()->validationAttributes($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        } elseif ($this->controller()->model && method_exists($this->controller()->model, 'validationAttributes')) {
            return $this->controller()->model::getValidationAttributes($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
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
        $this->action = $action;
        $auth = true;
        if (in_array($action, array_keys(Gate::abilities()))) {
            $middlewares = $this->route()->getAction('middleware');
            if (is_array($middlewares)) $middlewares = array_unique($middlewares);
            if (in_array('api', is_array($middlewares) && count($middlewares) ? $middlewares : [$middlewares])) return $auth;
            if (!auth()->check()) {
                auth()->login(User::guest());
            }
            $args = array_values($this->route()->parameters());
            try {
                $auth = $this->controller()->authorize($action, $args);
                if ($auth instanceof \Illuminate\Auth\Access\Response)
                    $auth = $auth->allowed();
            } catch (\Throwable $e) {
                $auth = false;
            }
        }
        if ($auth && $this->controller() && method_exists($this->controller(), 'gate')) {
            $auth = $this->controller()->gate($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        }
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
            $rules = $this->controller()->rules($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters())) ?: [];
            $this->controller()->setFillable($this->route()->getActionMethod(), array_keys($rules));
        } elseif ($this->controller()->model && method_exists($this->controller()->model, 'rules')) {
            $rules = $this->controller()->model::getRules($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters())) ?: [];
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
