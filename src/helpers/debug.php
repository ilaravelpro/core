<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

function result_message(&$array, $text)
{
    $array['message'] = strtoupper(preg_replace("/[\.!]/", '', str_replace(' ', '_', $text)));
    $array['message_text'] = _t($text);
}
