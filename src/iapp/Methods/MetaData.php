<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 11:25 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Methods;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
{
    use \iLaravel\Core\iApp\Methods\Data;
    protected $guarded = [];


    protected $markForDeletion = false;

    public function markForDeletion($bool = true)
    {
        $this->markForDeletion = $bool;
    }

    public function isMarkedForDeletion()
    {
        return (bool) $this->markForDeletion;
    }

    public function setValueAttribute($value)
    {
        return $this->renderSetValue($value);
    }

    public function getValueAttribute($value)
    {
        return $this->renderGetValue($value);
    }
}
