<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/22/20, 12:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

function result_message(&$array, $text, $values = null)
{
    if (is_array($text)) list($text, $values) = $text;
    $array['message'] = strtoupper(preg_replace("/[\.!]/", '', str_replace(' ', '_', $text)));
    $array['message_text'] = _t($text, $values);
}
