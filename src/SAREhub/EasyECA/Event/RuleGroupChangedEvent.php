<?php

namespace SAREhub\EasyECA\Event;

class RuleGroupChangedEvent
{
    /**
     * @var string
     */
    private $groupId;

    /**
     * @var array
     */
    private $rules;

    public function __construct(string $groupId, array $rules)
    {
        $this->groupId = $groupId;
        $this->rules = $rules;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
