<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/8/21, 9:58 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

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
    $result = (rad2deg(atan2($y, $x)) + 360) / 360;
    return round(($result - (int)$result) * 360, $precision);
}

function _uv2ddff($u, $v)
{
    $wind = ['speed' => 0, 'dir' => 0];
    if($v==0) {
        if ($u == 0) return $wind;
        if ($u > 0) return ['speed' => $u, 'dir' => 270];
        else return ['speed' => $u, 'dir' => 90];
    }if($v<0)
        $wind['dir']= atan($u/$v)*180/pi();
    else
        $wind['dir']= atan($u/$v)*180/pi()+180;
    if($wind['dir'] <0)
        $wind['dir']= $wind['dir']+360;
    $wind['speed'] = sqrt($u*$u+$v*$v);
    return $wind;
}

function _find_second_point($start, $dist, $bearing, $precision = 4)
{
    $radius = 6378.1;
    $lon = deg2rad($start[0]);
    $lat = deg2rad($start[1]);
    $brng  = deg2rad($bearing);
    $lat2 = asin(sin($lat) * cos($dist / $radius) + cos($lat) * sin($dist / $radius) * cos($brng));
    $lon2 = $lon + atan2(sin($brng) * sin($dist / $radius) * cos($lat), cos($dist / $radius) - sin($lat) * sin($lat2));
    return [
        round(rad2deg($lon2), $precision),
        round(rad2deg($lat2), $precision),
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

function even(array $numbers) {
    return array_filter($numbers, function ($number) {
        return $number % 2 == 0;
    });
}

function odd(array $numbers) {
    return array_filter($numbers, function ($number) {
        return !($number % 2 == 0);
    });
}

function _pa_to_alt($num, $h = false) {
    if (!$h) $num = $num / 100;
    return (pow(10,log10($num / 1013.25) / 5.2558797) - 1)/ (-6.8755856*pow(10,-6));
}

function _alt_to_pa($num, $h = false) {
    $calc = 1013.25 * pow((1 - 6.87535 * pow(10,-6) * $num), 5.2561);
    return $h ? $calc : $calc * 100;
}

function WGS84Lat($latitude, $nordsud = 1)
{
    $lat_degrees = (float) _get_value($latitude, '0', 0);
    $lat_minutes = (float) _get_value($latitude, '1', 0);
    $lat_minutes = $lat_minutes ? $lat_minutes / 60 : $lat_minutes;
    $lat_seconds = (float) _get_value($latitude, '2', 0);
    $lat_seconds = $lat_seconds ? $lat_seconds / 3600 : $lat_seconds;

    $lat = $nordsud * ($lat_degrees + $lat_minutes + $lat_seconds);
    return round(round($lat*10000000)/10000000, 6);
}

function WGS84Lng($longitude, $estouest = 1)
{
    $lng_degrees = (float) _get_value($longitude, '0', 0);
    $lng_minutes = (float) _get_value($longitude, '1', 0);
    $lng_minutes = $lng_minutes ? $lng_minutes / 60 : $lng_minutes;
    $lng_seconds = (float) _get_value($longitude, '2', 0);
    $lng_seconds = $lng_seconds ? $lng_seconds / 3600 : $lng_seconds;

    $lng = $estouest * ($lng_degrees + $lng_minutes + $lng_seconds);
    return round($lng, 6);
}

function WGS84LL($longitude, $latitude, $nordsud = 1, $estouest = 1)
{
    return [WGS84Lat($latitude, $nordsud), WGS84Lng($longitude, $estouest)];
}
