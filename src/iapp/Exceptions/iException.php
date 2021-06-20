<?php


namespace iLaravel\Core\iApp\Exceptions;
use Exception;

class iException extends Exception
{
    /**
     * Values for replace.
     *
     * @var array
     */
    protected $replace_values;
    /**
     * Create a new authentication exception.
     *
     * @param  string  $message
     * @param  array  $guards
     * @param  string|null  $redirectTo
     * @return void
     */
    public function __construct($message = 'Unauthenticated.', array $replace_values = null)
    {
        parent::__construct($message);
        $this->replace_values = $replace_values;
    }

    /**
     * Get the guards that were checked.
     *
     * @return array
     */
    public function replace_values()
    {
        return $this->replace_values;
    }

}
