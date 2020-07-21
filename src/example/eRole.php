<?php

namespace iLaravel\Core\Example;

use App\User;
use iLaravel\Core\iApp\Role;

class eRole
{
    public static function handel() {
     /*   $role = Role::create([
            'name' => 'officer',
            'title' => 'Officer',
        ]);*/
        foreach (iconfig('scopes.flight_plans') as $scope) {
            //$role->scopes()->create(['scope' => "flight_plans.$scope", 'can' => true]);
            User::find(1)->scopes()->create(['scope' => "users.$scope", 'can' => true]);
        }
    }

}
