<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 7:40 PM
 * Copyright (c) 2021. Powered by iamir.net
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
