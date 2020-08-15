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

function _getBearingGeo($lonlat1, $lonlat2, $precision = 2)
{
    $lat1 = deg2rad((float)$lonlat1[1]);
    $lat2 = deg2rad((float)$lonlat2[1]);
    $lon1 = deg2rad((float)$lonlat1[0]);
    $lon2 = deg2rad((float)$lonlat2[0]);
    $dLon = $lon2 - $lon1;
    $y = sin($dLon) * cos($lat2);
    $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLon);
    $result = (atan2($y, $x) * 180 / pi() + 360) / 360;
    return round(($result - (int)$result) * 360, $precision);
}

function _uv2ddff($u, $v)
{
    $u2v2 = $u ^ 2 + $v ^ 2;
    $wind['speed'] = sqrt($u2v2 < 0 ? $u2v2 * -1 : $u2v2);
    //$wind['dir'] = 270 - (atan2($v, $u) * (180 / pi()));
    $wind['dir'] = windDir($u, $v);
    return $wind;
}

function windDir($u, $v)
{
    if ($u > 0) return ((180 / pi()) * atan($u / $v) + 180);
    if ($u < 0 & $v < 0) return ((180 / pi()) * atan($u / $v) + 0);
    if ($u > 0 & $v < 0) return ((180 / pi()) * atan($u / $v) + 360);
}

function _find_second_point($start, $dist, $bearing, $precision = 4)
{
    $dx = $dist * sin(deg2rad($bearing));
    $dy = $dist * cos(deg2rad($bearing));
    $delta_longitude = $dx / (111320 * cos(deg2rad($start[1])));
    $delta_latitude = $dy / 110540;
    return [
        round($start[0] + $delta_longitude, $precision),
        round($start[1] + $delta_latitude, $precision),
    ];
}

function _toNM($number)
{
    return round($number * 0.000539956803, 2);
}

function _NMtoM($number)
{
    return round($number * 1852, 2);
}

