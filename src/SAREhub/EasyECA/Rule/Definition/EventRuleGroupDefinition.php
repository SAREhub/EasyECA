<?php


namespace SAREhub\EasyECA\Rule\Definition;


class EventRuleGroupDefinition implements \JsonSerializable
{
    /**
     * @var string
     */
    private $eventType;

    /**
     * @var RuleGroupDefinition
     */
    private $ruleGroup;


    public function __construct(string $eventType, RuleGroupDefinition $ruleGroup)
    {
        $this->eventType = $eventType;
        $this->ruleGroup = $ruleGroup;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            "eventType" => $this->getEventType(),
            "ruleGroup" => $this->getRuleGroup()
        ];
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getRuleGroup(): RuleGroupDefinition
    {
        return $this->ruleGroup;
    }
}