<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 5:35 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Methods;


use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\iApp\Role;

class Scopes
{
    public static function parse(Request $request, $type, $parent)
    {
        switch ($type) {
            case 'user':
                $model = imodal('UserScope');
                $mparent = imodal('User');
                $parent = is_string($parent) ? $mparent::findBySerial($parent) : $parent;
                break;
            case 'role':
                $model = imodal('RoleScope');
                $mparent = imodal('Role');
                $parent = is_string($parent) ? $mparent::findBySerial($parent) : $parent;
                break;
        }
        $positions = [];
        $configScopes = iconfig('scopes', []);
        if (isset($configScopes['users']['items']) && $configScopes['users']['items']) {
            $configScopes['users']['items']['fields']['role'] = Role::all()->pluck('name')->toArray();
        }
        unset($configScopes['global']);
        global $trans;
        $trans = [];
        foreach ($configScopes as $key => $section) {
            $positions[$key] = [
                'title' => _t($section['title']),
                'name' => $key,
            ];
            foreach ($section['items'] as $skey => $scope) {
                list($positions, $trans) = static::renderScopes($model, $positions, $parent, $scope,$key, is_array($scope) ? "$key.$skey" : "$key.$scope", 0);
            }
        }
        return ['data' => $positions];
    }

    public static function renderScopes($model, $positions, $parent, $scope, $key, $skey, $canDef = 0)
    {
        global $trans;
        if (is_array($scope)) {
            foreach ($scope as $i => $valued)
                list($positions, $trans) = static::renderScopes($model, $positions, $parent, $valued,$key, is_array($valued) ? "$skey.$i" : "$skey.$valued", $canDef);
        } else {
            $smodel = $model::where(['role_id' => $parent->id, 'scope' => $skey])->first();
            $positions[$key]['scopes'][] = [
                'id' => $smodel ? $smodel->serial : null,
                'parent_id' => $parent->serial,
                'title' => implode(' ', array_map(function ($key){
                    global $trans;
                    return isset($trans[$key]) ? $trans[$key] : ($trans[$key] = ucfirst(_t($key)));
                }, array_filter(explode('.', $skey), function ($skey) use($key) {return $skey !== $key;}))),
                'scope' =>  $skey,
                'can' => $smodel ? $smodel->can : 0
            ];
        }
        return[$positions, $trans];
    }
}
