<?php

namespace Sid\Phalcon\Cron\Job;

use Sid\Phalcon\Cron\Job;

class System extends Job
{
    protected string $command;
    protected ?string $output;

    /**
     * @param string $expression
     * @param string $command
     * @param string|null $output
     */
    public function __construct(string $expression, string $command, ?string $output = null)
    {
        parent::__construct($expression);

        $this->command = $command;
        $this->output  = $output;
    }

    /**
     * @return string
     */
    public function getCommand() : string
    {
        return $this->command;
    }

    /**
     * @return string|null
     */
    public function getOutput(): ?string
    {
        return $this->output;
    }

    /**
     * @return string
     */
    private function buildCommand() : string
    {
        $command = $this->getCommand();
        $output  = $this->getOutput();

        if ($output) {
            $command .= " > " . $output . " 2>&1";
        }

        return $command;
    }

    /**
     * @return string|null
     */
    public function runInForeground(): ?string
    {
        return shell_exec($this->buildCommand()) ?: null;
    }
}
