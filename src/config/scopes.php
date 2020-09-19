<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

include 'preferences.php';
$scopes = [];

$scopes['global'] = [
    'view' => $preferences['scopeSubs'],
    'create',
    'edit' => $preferences['scopeSubs'],
    'destroy' => $preferences['scopeSubs']
];

$scopes['users'] = $scopes['global'];
$scopes['roles'] = $scopes['global'];
