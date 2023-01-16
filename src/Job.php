<?php

namespace Sid\Phalcon\Cron;

use Cron\CronExpression;
use DateTime;
use Phalcon\Di\Injectable;
use Sid\Cron\JobInterface;

abstract class Job extends Injectable implements JobInterface
{
    protected string $expression;

    /**
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function getExpression() : string
    {
        return $this->expression;
    }

    /**
     * @param \DateTime|null $datetime
     * @return bool
     */
    public function isDue(DateTime $datetime = null) : bool
    {
        $cronExpression = CronExpression::factory(
            $this->getExpression()
        );

        return $cronExpression->isDue($datetime);
    }

    /**
     * @return mixed
     */
    abstract public function runInForeground(): mixed;

    /**
     * @throws Exception
     */
    public function runInBackground() : Process
    {
        $processID = pcntl_fork();

        if ($processID === -1) {
            throw new Exception(
                "Failed to fork process."
            );
        }

        // This is the child process.
        if ($processID === 0) {
            // @codeCoverageIgnoreStart
            $this->runInForeground();

            exit(0);
            // @codeCoverageIgnoreEnd
        }

        return new Process($processID);
    }
}
