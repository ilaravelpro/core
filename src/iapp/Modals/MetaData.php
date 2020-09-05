<?php


namespace iLaravel\Core\iApp\Modals;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
{
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
