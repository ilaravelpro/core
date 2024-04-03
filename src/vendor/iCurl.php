<?php

namespace iLaravel\Core\Vendor;

class iCurl
{
    private static function post($url, $data, $headers = [], $options = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt_array($curl, $options);
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new \Exception(curl_error($curl));
        }
        curl_close($curl);
        return self::json_decode($output, $url);
    }

    private static function get($url, $params = [], $headers = [], $options = [])
    {
        $endpoint = count($params) ? ("{$url}?" . http_build_query($params, '', '&')) : $url;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt_array($curl, $options);
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new \Exception(curl_error($curl));
        }
        curl_close($curl);
        return self::json_decode($output, $url);
    }

    private static function other($method, $url, $params = [], $headers = [], $options = [])
    {
        $endpoint = count($params) ? ("{$url}?" . http_build_query($params, '', '&')) : $url;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt_array($curl, $options);
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new \Exception(curl_error($curl));
        }
        curl_close($curl);
        return self::json_decode($output, $url);
    }

    public static function json_decode($string)
    {
        if (!$string || is_array($string))
            return $string;
        $output = json_decode($string, true);
        return (json_last_error() == JSON_ERROR_NONE) ? $output : $string;
    }

    public static function request($base, $url, $params = [], $headers = [], $method = "GET", $options = [])
    {
        $oheaders = [];
        foreach ($headers as $index => $header) {
            $oheaders[] = "{$index}: {$header}";
        }
        $endpoint = "{$base}{$url}";
        switch ($method) {
            case "GET":
                $result = self::get($endpoint, $params, $oheaders, $options);
                break;
            case "POST":
                $result = self::post($endpoint, $params, $oheaders, $options);
                break;
            default:
                $result = self::other($method, $endpoint, $params, $oheaders, $options);
                break;
        }
        return $result;
    }
}
