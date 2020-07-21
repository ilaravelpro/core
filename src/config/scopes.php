<?php
include 'preferences.php';
$scopes = [];

$scopes['global'] = [
    'view' => $preferences['scopeSubs'],
    'create',
    'edit' => $preferences['scopeSubs'],
    'destroy' => $preferences['scopeSubs']
];

$scopes['users'] = $scopes['global'];
$scopes['roles'] = $scopes['global'];
