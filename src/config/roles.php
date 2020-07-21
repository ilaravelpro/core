<?php
$roles["user"] = [
    "roles" => [

    ]
];

$roles["admin"] = [
    "roles" => array_merge($roles["user"]["roles"], [
        "user",
        "admin",
    ])
];

$roles["super"] = [
    "roles" => array_merge($roles["admin"]["roles"], ["super"])
];
