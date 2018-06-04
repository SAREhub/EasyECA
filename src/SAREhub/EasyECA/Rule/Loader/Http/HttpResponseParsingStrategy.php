<?php

namespace SAREhub\EasyECA\Rule\Loader\Http;


use Psr\Http\Message\ResponseInterface;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;

interface HttpResponseParsingStrategy
{
    /**
     * @param ResponseInterface $response
     * @return EventRuleGroupDefinition[]
     */
    public function parse(ResponseInterface $response): array;
}
