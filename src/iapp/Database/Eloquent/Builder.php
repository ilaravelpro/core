<?php

namespace iLaravel\Core\iApp\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\Cache;

class Builder extends EloquentBuilder
{
    protected $cacheEnabled = true;
    protected $cacheTtl = 60;
    protected $cacheKey = null;

    public function enableCache($ttl = 60, $key = null)
    {
        $this->cacheEnabled = true;
        $this->cacheTtl = $ttl;
        $this->cacheKey = $key;
        return $this;
    }

    public function disableCache()
    {
        $this->cacheEnabled = false;
        return $this;
    }
    protected function runSelect()
    {
        if (!$this->cacheEnabled)
            return parent::runSelect();
        $cacheKey = $this->cacheKey ?? $this->generateCacheKey();
        dd($cacheKey);
        return unserialize(Cache::remember($cacheKey, $this->cacheTtl, function () use ($columns) {
            return  serialize(parent::runSelect());
        }));
    }


    protected function generateCacheKey()
    {
        $query = $this->getQuery();
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        return "ilaravel:db:{$this->getModel()->getTable()}" . md5( $sql . serialize($bindings));
    }
}
