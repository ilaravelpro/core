<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/3/21, 5:35 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Methods;


use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

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
        unset($configScopes['global']);
        foreach ($configScopes as $key => $section) {
            $positions[$key] = [
                'title' => $section['title'],
                'name' => $key,
            ];
            foreach ($section['items'] as $skey => $scope) {
                $scopes =  is_array($scope) ? $scope : [$scope];
                foreach ($scopes as $jscope) {
                    $smodel = $model::where(['role_id' => $parent->id, 'scope' => "$key.".(is_string($skey) ? "$skey.": '')  .$jscope])->first();
                    $positions[$key]['scopes'][] = [
                        'id' => $smodel ? $smodel->serial : null,
                        'parent_id' => $parent->serial,
                        'title' => (is_string($skey) ? ucfirst($skey) . " " : '') . ucfirst($jscope),
                        'scope' =>  "$key.".(is_string($skey) ? "$skey.": '') .$jscope,
                        'can' => $smodel ? $smodel->can : 0
                    ];
                }
            }
        }
        return ['data' => $positions];
    }
}
