<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
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
