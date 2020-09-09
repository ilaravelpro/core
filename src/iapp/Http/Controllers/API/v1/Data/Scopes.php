<?php


namespace iLaravel\Core\iApp\Http\Controllers\API\v1\Data;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

trait Scopes
{
    public function scopes(Request $request, $type, $parent)
    {
        switch ($type) {
            case 'user':
                $model = imodal('UserScope');
                $mparent = imodal('User');
                $parent = $mparent::findBySerial($parent);
                break;
            case 'role':
                $model = imodal('RoleScope');
                $mparent = imodal('Role');
                $parent = $mparent::findBySerial($parent);
                break;
        }
        $positions = [];
        $configScopes = iconfig('scopes', []);
        unset($configScopes['global']);
        foreach ($configScopes as $key => $section) {
            $positions[$key] = [
                'title' => ucfirst($key),
                'name' => $key,
            ];
            foreach ($section as $skey => $scope) {
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
