<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

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
