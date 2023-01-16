<?php

namespace Sid\Phalcon\Cron\Job;

use Sid\Phalcon\Cron\Job;

class Callback extends Job
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param string $expression
     * @param callable $callback
     */
    public function __construct(string $expression, callable $callback)
    {
        parent::__construct($expression);
        
        $this->callback = $callback;
    }

    /**
     * @return callable
     */
    public function getCallback() : callable
    {
        return $this->callback;
    }
    
    /**
     * @return mixed
     */
    public function runInForeground()
    {
        return call_user_func($this->callback);
    }
}
