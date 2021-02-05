<?php

/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
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
