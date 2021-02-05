<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 7:09 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

$routes = [
    'api' => [
        'status' => true,
        'users' => ['status' => true,],
        'roles' => ['status' => true,]
    ],
    'web' => [
        'status' => false,
        'users' => ['status' => true,],
        'roles' => ['status' => true,]
    ],
    'auth' => [
        'status' => false,
    ],
];
?>
