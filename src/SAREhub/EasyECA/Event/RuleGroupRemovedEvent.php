<?php

namespace SAREhub\EasyECA\Event;

class RuleGroupRemovedEvent
{
    /**
     * @var string
     */
    private $groupId;

    public function __construct(string $groupId)
    {
        $this->groupId = $groupId;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }
}
