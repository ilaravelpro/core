<?php



/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/17/20, 7:08 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
{
    use \iLaravel\Core\iApp\Modals\Data;
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
