<?php

namespace SAREhub\EasyECA\Event;

use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\Client\Message\Message;
use SAREhub\Client\Processor\SplittingStrategy;
use SAREhub\Commons\Misc\ArrayHelper;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinitionFactory;

class DefaultEventTypeRuleGroupsSplittingStrategy implements SplittingStrategy
{
    /**
     * @var EventRuleGroupDefinitionFactory
     */
    private $definitionFactory;

    public function __construct(EventRuleGroupDefinitionFactory $definitionFactory)
    {
        $this->definitionFactory = $definitionFactory;
    }

    public function split(Message $message): iterable
    {
        /** @var RuleGroupChangedEvent $inBody */
        $inBody = $message->getBody();
        return $this->splitRulesByEventType($inBody);
    }

    private function splitRulesByEventType(RuleGroupChangedEvent $ruleGroupChangedEvent): array
    {
        $groupedRules = $this->groupRulesByEventType($ruleGroupChangedEvent->getRules());
        $splits = [];
        foreach ($groupedRules as $eventType => $rules) {
            $definition = $this->createDefinition($ruleGroupChangedEvent->getGroupId(), $eventType, $rules);
            $splits[] = BasicExchange::withIn(BasicMessage::withBody($definition));
        }
        return $splits;
    }

    private function groupRulesByEventType(array $rules): array
    {
        return ArrayHelper::groupByKey($rules, "event");
    }

    private function createDefinition(string $groupId, string $eventType, array $rules): EventRuleGroupDefinition
    {
        $data = [
            "id" => $groupId,
            "rules" => $rules
        ];
        return $this->definitionFactory->create($eventType, $data);
    }

}