<?php

function _getDistanceGeo($c1, $c2, $opt_radius = 6371008.8)
{
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

function _getBearingGeo($lonlat1, $lonlat2)
{
    $p1 = $lonlat1[1] * pi() / 180;
    $p2 = $lonlat2[1] * pi() / 180;
    $a1 = $lonlat1[0] * pi() / 180;
    $a2 = $lonlat2[0] * pi() / 180;
    $y = sin($a2 - $a1) * cos($p2);
    $x = cos($p1) * sin($p2) - sin($p1) * cos($p2) * cos($a2 - $a1);
    $result = (atan2($y, $x) * 180 / pi() + 360) / 360;
    return round(($result - (int) $result) * 360);
}

function _toNM($number)
{
    return round($number * 0.000539956803, 2);
}

