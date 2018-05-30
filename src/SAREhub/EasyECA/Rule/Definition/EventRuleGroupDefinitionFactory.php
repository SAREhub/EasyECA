<?php

namespace SAREhub\EasyECA\Rule\Definition;


class EventRuleGroupDefinitionFactory
{
    /**
     * @var RuleGroupDefinitionFactory
     */
    private $ruleGroupDefinitionFactory;

    public function __construct(RuleGroupDefinitionFactory $ruleGroupDefinitionFactory)
    {
        $this->ruleGroupDefinitionFactory = $ruleGroupDefinitionFactory;
    }

    public function create(string $eventType, array $data): EventRuleGroupDefinition
    {
        return new EventRuleGroupDefinition($eventType, $this->ruleGroupDefinitionFactory->create($data));
    }
}