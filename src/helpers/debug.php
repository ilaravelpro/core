<?php

function result_message(&$array, $text)
{
    $array['message'] = strtoupper(preg_replace("/[\.!]/", '', str_replace(' ', '_', $text)));
    $array['message_text'] = _t($text);
}
