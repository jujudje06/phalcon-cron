<?php

namespace Sid\Phalcon\Cron\Job;

use Phalcon\Di\DiInterface;
use Sid\Phalcon\Cron\Exception;
use Sid\Phalcon\Cron\Job;

class Phalcon extends Job
{
    protected ?array $body;

    /**
     * @param string $expression
     * @param array|null $body
     *
     * @throws \Sid\Phalcon\Cron\Exception
     */
    public function __construct(string $expression, ?array $body = null)
    {
        $di = $this->getDI();

        if (!($di instanceof DiInterface)) {
            throw new Exception(
                "A dependency injection object is required to access internal services"
            );
        }

        parent::__construct($expression);

        $this->body = $body;
    }

    /**
     * @return array|null
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function runInForeground() : string
    {
        $di = $this->getDI();
        $console = $di->get("console");

        ob_start();

        $console->handle(
            $this->getBody()
        );

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }
}
