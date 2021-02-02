<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

$resources = [
    'global' => [

    ],
    'users' => [
        'data' => [
            'text' => 'fullname'
        ],
        'hidden' => [
            'global' => [
            ],
            'guest' => [
                'role'
            ],
            'user' => [

            ]
        ]
    ],
    'user_scopes' => [
        'hidden' => [
            'global' => [
                'user_id',
            ]
        ]
    ],
    'roles' => [
        'data' => [
            'text' => 'title',
            'value' => 'name',
        ],
    ],
    'role_scopes' => [
        'hidden' => [
            'global' => [
                'role_id',
            ]
        ]
    ],
];

?>
