<?php

function _getDistanceGeo($c1, $c2, $opt_radius = 6371008.8) {
    $radius = $opt_radius;
    $lat1 = deg2rad($c1[1]);
    $lat2 = deg2rad($c2[1]);
    $deltaLatBy2 = ($lat2 - $lat1) / 2;
    $deltaLonBy2 = deg2rad($c2[0] - $c1[0]) / 2;
    $a = sin($deltaLatBy2) * sin($deltaLatBy2) +
        sin($deltaLonBy2) * sin($deltaLonBy2) *
        cos($lat1) * cos($lat2);
    return 2 * $radius * atan2(sqrt($a), sqrt(1 - $a));
}

function _toNM($number) {
    return round($number  * 0.000539956803, 2);
}
