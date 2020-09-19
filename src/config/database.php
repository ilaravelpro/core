<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
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
