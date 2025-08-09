<?php
function get_table_name_from_model(string $modelClass): ?string {
    return class_exists($modelClass) && method_exists($m = new $modelClass, 'getTable') ? $m->getTable() : false;
}

function make_table_name(string|array $names): string
{
    $names = array_map("sanitize_column_name", is_string($names) ? [$names] : $names);
    return array_walk($names, function ($v, $k) {
        return "{$k}_{$v}";
    });
}

function sanitize_column_name(string $name): string
{
    return trim(strtolower(preg_replace('/[^a-z0-9_]+/i', '_', $name)), '_');
}