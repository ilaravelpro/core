<?php

namespace iLaravel\Core\iApp\Http\Validators;



use Illuminate\Support\Str;

class SerialHas
{
    public function validate($attribute, $value, $parameters,$validator) {
        if (!isset($parameters[0])) return false;
        $model = imodal(ucfirst($parameters[0]));
        if (!class_exists($model)) {
            list($table) = explode(',', $parameters[0]);
            $table = Str::camel($table);
            $model = imodal(ucfirst(Str::singular($table)));
        }
        if (is_array($value)) {
            $value = array_unique($value);
            foreach ($value as $dk => $dv) {
                $value[$dk] = $model::encode_id($dv);
            }
        } else {
            $value = $model::encode_id($value);
        }
        return true;
    }
}
