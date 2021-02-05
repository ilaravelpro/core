<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

$database = [
    'migrations' => [
        'users' => [
            'creator' => true,
            'agent' => true,
        ]
    ],
    'operators' => [
        [
            'text' => 'is',
            'value' => 'is',
            'symbol' => '=',
        ],
        [
            'text' => 'is not',
            'value' => 'not',
            'symbol' => '!=',
        ],
        [
            'text' => 'greater than',
            'value' => 'greater',
            'symbol' => '>',
        ],
        [
            'text' => 'less than',
            'value' => 'less',
            'symbol' => '<',
        ],
    ],
];
?>
