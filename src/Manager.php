<?php

namespace Sid\Phalcon\Cron;

use DateTime;
use Sid\Cron\Manager as SidCronManager;

class Manager extends SidCronManager
{
    /**
     * For background jobs.
     */
    protected array $processes = [];

    /**
     * @param string $filename
     * @return void
     * @throws \Sid\Phalcon\Cron\Exception
     */
    public function addCrontab(string $filename): void
    {
        $crontab = new CrontabParser($filename);
        foreach ($crontab->getJobs() as $job) {
            $this->add($job);
        }
    }

    /**
     * Run all due jobs in the foreground.
     */
    public function runInForeground(DateTime $now = null) : array
    {
        $jobs = $this->getDueJobs($now);

        $outputs = [];
        foreach ($jobs as $job) {
            $outputs[] = $job->runInForeground();
        }

        return $outputs;
    }

    /**
     * Run all due jobs in the background.
     */
    public function runInBackground(DateTime $now = null) : array
    {
        $jobs = $this->getDueJobs($now);

        foreach ($jobs as $job) {
            $this->processes[] = $job->runInBackground();
        }

        return $this->processes;
    }

    /**
     * Wait for all jobs running in the background to finish.
     */
    public function wait(): void
    {
        foreach ($this->processes as $process) {
            $process->wait();
        }
    }

    /**
     * Terminate all jobs running in the background.
     */
    public function terminate(): void
    {
        foreach ($this->processes as $process) {
            $process->terminate();
        }
    }

    /**
     * Kill all jobs running in the background.
     */
    public function kill(): void
    {
        foreach ($this->processes as $process) {
            $process->kill();
        }
    }
}
