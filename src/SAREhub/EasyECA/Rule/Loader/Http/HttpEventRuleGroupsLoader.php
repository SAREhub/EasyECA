<?php

namespace SAREhub\EasyECA\Rule\Loader\Http;


use SAREhub\EasyECA\Rule\Loader\EventRuleGroupsLoader;
use SAREhub\EasyECA\Util\HttpGetRequestCommand;

class HttpEventRuleGroupsLoader implements EventRuleGroupsLoader
{
    /**
     * @var HttpGetRequestCommand
     */
    private $getRequestCommand;

    /**
     * @var HttpResponseParsingStrategy
     */
    private $strategy;

    public function __construct(HttpGetRequestCommand $getRequestCommand, HttpResponseParsingStrategy $strategy)
    {
        $this->getRequestCommand = $getRequestCommand;
        $this->strategy = $strategy;
    }

    public function load(): array
    {
        return $this->strategy->parse($this->getRequestCommand->execute());
    }
}
