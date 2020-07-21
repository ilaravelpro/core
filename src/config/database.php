<?php
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
