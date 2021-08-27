<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/8/21, 5:19 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\Vendor;


class HttpRequest
{
    public static function download($url, $out){
        $fp = fopen($out, 'w+');
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, '');
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_exec($curl);
        if (curl_errno($curl) || curl_getinfo($curl, CURLINFO_HTTP_CODE) == 404) {
            if (file_exists($out)) unlink($out);
            return false;
        }
        curl_close($curl);
        fclose($fp);
        return $out;
    }

    public static function get($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_POST, false );
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false );
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public static function has($url){
        $file_headers = get_headers($url);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        else {
            return $url;
        }
    }
}
