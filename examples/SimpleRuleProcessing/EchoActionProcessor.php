<?php

namespace SAREhub\Example\SimpleRuleProcessing;

use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;

class EchoActionProcessor implements Processor
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function process(Exchange $exchange)
    {
        echo $this->message;
    }
}