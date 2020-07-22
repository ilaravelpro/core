<?php
include 'auth.php';
include 'database.php';
include 'roles.php';
include 'scopes.php';
include 'status.php';
include 'types.php';
include 'classes.php';
include 'countries.php';
include 'resources.php';
include 'routes.php';
include 'preferences.php';
include 'plugins.php';

return [
    "auth" => $auth,
    "database" => $database,
    "roles" => $roles,
    "scopes" => $scopes,
    "status" => $status,
    "types" => $types,
    "classes" => $classes,
    "countries" => $countries,
    "resources" => $resources,
    "routes" => $routes,
    "preferences" => $preferences,
    "plugins" => $plugins,
];
?>
