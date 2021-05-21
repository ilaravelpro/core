<?php
/*
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 5/20/21, 12:56 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Methods;


trait LogLazyLoading
{
    /**
     * Get a relationship value from a method.
     *
     * @param  string $method
     *
     * @return mixed
     *
     * @throws \LogicException
     * @throws \Exception
     */
    protected function getRelationshipFromMethod($method)
    {
        $modelName = static::class;

        $exception = new \Exception(
            "Attempting to lazy-load relation '$method' on model '$modelName'"
        );

        if (property_exists($this, 'disableLazyLoading') && $this->disableLazyLoading) {
            throw $exception;
        }

        report($exception);

        return parent::getRelationshipFromMethod($method);
    }
}
