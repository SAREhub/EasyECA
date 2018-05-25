<?php


namespace SAREhub\EasyECA\Rule\Definition;


class EventRuleGroupsDefinition implements \JsonSerializable
{
    /**
     * @var string
     */
    private $eventType;

    /**
     * @var RuleGroupDefinition[]
     */
    private $ruleGroups;


    public function __construct(string $eventType, array $ruleGroups)
    {
        $this->eventType = $eventType;
        $this->ruleGroups = $ruleGroups;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            "eventType" => $this->getEventType(),
            "ruleGroups" => $this->getRuleGroups()
        ];
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getRuleGroups(): array
    {
        return $this->ruleGroups;
    }
}