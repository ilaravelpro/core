<?php

namespace iLaravel\Core\iApp\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\Cache;

class Builder extends EloquentBuilder
{
    protected $cacheEnabled = true;
    public $cacheTtl = 60;
    public $cacheKey = null;

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
    public function get($columns = ['*'])
    {
        if (!$this->cacheEnabled)
            return parent::get($columns);
        $cacheKey = $this->cacheKey ?? $this->generateCacheKey();
        return unserialize(Cache::remember($cacheKey . ":get", $this->cacheTtl, function () use ($columns, $cacheKey) {
            $result = parent::get($columns);
            $result->cacheKey = $cacheKey . ":get";
            return serialize($result);
        }));
    }

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        if (!$this->cacheEnabled)
            return parent::paginate($perPage, $columns, $pageName, $page);
        $cacheKey = $this->cacheKey ?? $this->generateCacheKey();
        return unserialize(Cache::remember($cacheKey . ":paginate:{$perPage}:$page", $this->cacheTtl, function () use ($cacheKey, $perPage, $columns, $pageName, $page) {
            $result = parent::paginate($perPage, $columns, $pageName, $page);
            $result->cacheKey = $cacheKey . ":paginate:{$perPage}:$page";
            return serialize($result);
        }));
    }

    protected function generateCacheKey()
    {
        $query = $this->getQuery();
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        return "ilaravel:db:{$this->getModel()->getTable()}:" . md5( $sql . serialize($bindings));
    }
}
