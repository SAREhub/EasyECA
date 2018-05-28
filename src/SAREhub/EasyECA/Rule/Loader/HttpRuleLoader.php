<?php

namespace SAREhub\EasyECA\Rule\Loader;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupsDefinitionFactory;
use SAREhub\EasyECA\Rule\Loader\ParsingStrategy\HttpResponseParsingStrategy;

class HttpRuleLoader implements EventRulesLoader
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $url;

    /**
     * @var HttpResponseParsingStrategy
     */
    private $strategy;

    /**
     * @var EventRuleGroupsDefinitionFactory
     */
    private $eventRuleGroupsDefinitionFactory;

    public function __construct(
        Client $client,
        string $url,
        HttpResponseParsingStrategy $strategy,
        EventRuleGroupsDefinitionFactory $factory
    ) {
        $this->client = $client;
        $this->url = $url;
        $this->strategy = $strategy;
        $this->eventRuleGroupsDefinitionFactory = $factory;
    }

    public function load(): array
    {
        $decodedResponseBody = $this->getDecodedResponseBody($this->client->get($this->url));
        return $this->getEventGroupsData($this->strategy->load($decodedResponseBody));
    }

    private function getDecodedResponseBody(ResponseInterface $response): array
    {
        return json_decode($response->getBody(), true);
    }

    protected function getEventGroupsData($parsedData): array
    {
        $eventGroups = [];
        foreach ($parsedData as $eventType => $ruleGroupsData) {
            $eventGroups[] = $this->eventRuleGroupsDefinitionFactory->create($eventType, $ruleGroupsData);
        }
        return $eventGroups;
    }
}