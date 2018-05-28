<?php

namespace SAREhub\EasyECA\Rule;


use SAREhub\EasyECA\Rule\Definition\EventRuleGroupsDefinition;

class RulesRegistry
{
    /**
     * @var EventRuleGroupsDefinition[]
     */
    private $eventRuleGroups = [];

    public function add(EventRuleGroupsDefinition $definition)
    {
        foreach ($this->eventRuleGroups as $key => $eventRuleGroup) {
            if ($eventRuleGroup->getEventType() === $definition->getEventType()) {
                $this->eventRuleGroups[$key] = $definition;
                return;
            }
        }
        $this->eventRuleGroups[] = $definition;
    }

    public function getByEventType(string $eventType): ?EventRuleGroupsDefinition
    {
        foreach ($this->eventRuleGroups as $eventRuleGroup) {
            if ($eventRuleGroup->getEventType() === $eventType) {
                return $eventRuleGroup;
            }
        }
        return null;
    }

    public function updateByEvent(string $eventType, EventRuleGroupsDefinition $definition)
    {
        foreach ($this->eventRuleGroups as $key => $eventRuleGroup) {
            if ($eventRuleGroup->getEventType() === $eventType) {
                $this->eventRuleGroups[$key] = $definition;
                break;
            }
        }
    }
}