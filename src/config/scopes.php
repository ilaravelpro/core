<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/27/21, 11:08 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

include 'preferences.php';
$scopes = [];

$scopes['global'] = [
    'title' => 'Global',
    'items' => [
        'view' => $preferences['scopeSubs'],
        'create',
        'edit' => $preferences['scopeSubs'],
        'destroy' => $preferences['scopeSubs'],
        'data' => $preferences['scopeSubs'],
    ]
];

$scopes['users'] = [
    'title' => 'Users',
    'items' => $scopes['global']['items']
];
$scopes['roles'] = [
    'title' => 'Roles',
    'items' => $scopes['global']['items']
];
