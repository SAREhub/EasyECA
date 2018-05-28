<?php

namespace SAREhub\EasyECA\Rule\Definition;


class EventRuleGroupsDefinitionFactory
{
    /**
     * @var RuleGroupDefinitionFactory
     */
    private $ruleGroupDefinitionFactory;

    public function __construct(RuleGroupDefinitionFactory $ruleGroupDefinitionFactory)
    {
        $this->ruleGroupDefinitionFactory = $ruleGroupDefinitionFactory;
    }

    public function create(string $eventType, array $data): EventRuleGroupsDefinition
    {
        $ruleGroups = [];
        foreach ($data as $ruleGroupData) {
            $ruleGroups[] = $this->ruleGroupDefinitionFactory->create($ruleGroupData);
        }
        return new EventRuleGroupsDefinition($eventType, $ruleGroups);
    }
}