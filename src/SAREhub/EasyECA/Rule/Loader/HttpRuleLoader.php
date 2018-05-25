<?php

namespace SAREhub\EasyECA\Rule\Loader;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupsDefinition;
use SAREhub\EasyECA\Rule\Definition\RuleDefinition;
use SAREhub\EasyECA\Rule\Definition\RuleGroupDefinition;
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
     * @var ActionDefinitionFactory
     */
    private $actionDefinitionFactory;

    public function __construct(
        Client $client,
        string $url,
        HttpResponseParsingStrategy $strategy,
        ActionDefinitionFactory $factory
    ) {
        $this->client = $client;
        $this->url = $url;
        $this->strategy = $strategy;
        $this->actionDefinitionFactory = $factory;
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
            $ruleGroups = $this->getRuleGroupsData($ruleGroupsData);
            $eventGroups[] = new EventRuleGroupsDefinition($eventType, $ruleGroups);
        }
        return $eventGroups;
    }

    protected function getRuleGroupsData($ruleGroupsData): array
    {
        $ruleGroups = [];
        foreach ($ruleGroupsData as $campaignId => $rulesData) {
            $rules = $this->getRulesData($rulesData, $campaignId);
            $ruleGroups[] = new RuleGroupDefinition($campaignId, $rules);
        }
        return $ruleGroups;
    }

    protected function getRulesData($rulesData, $campaignId): array
    {
        $rules = [];
        foreach ($rulesData as $ruleRow) {
            $rules[] = new RuleDefinition($ruleRow[$campaignId],
                $this->actionDefinitionFactory->create($ruleRow["onPass"]),
                $this->actionDefinitionFactory->create($ruleRow["onFail"]));
        }
        return $rules;
    }
}