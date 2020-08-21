<?php

namespace SAREhub\EasyECA\Rule;


use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;
use SAREhub\EasyECA\Util\MulticastGroupsRouter;

class EventRuleGroupManager
{
    /**
     * @var MulticastGroupsRouter
     */
    private $router;

    /**
     * @var RuleGroupParser
     */
    private $groupParser;

    /**
     * @var AddRuleGroupListener
     */
    private $addGroupListener;

    /**
     * @var RemoveGroupFromAllEventsListener
     */
    private $removeGroupFromAllEventsListener;

    public function __construct(MulticastGroupsRouter $router, RuleGroupParser $groupParser)
    {
        $this->router = $router;
        $this->groupParser = $groupParser;
    }

    public function add(EventRuleGroupDefinition $definition): void
    {
        $ruleGroup = $definition->getRuleGroup();
        $this->router->add($definition->getEventType(), $ruleGroup->getId(), $this->groupParser->parse($ruleGroup));

        if ($this->addGroupListener) {
            $this->addGroupListener->onAddRuleGroup($definition);
        }
    }

    public function removeGroupFromAllEvents(string $groupId): void
    {
        $this->router->removeFromAll($groupId);
        if ($this->removeGroupFromAllEventsListener) {
            $this->removeGroupFromAllEventsListener->onRemoveGroupFromAllEvents($groupId);
        }
    }

    public function setAddRuleGroupListener(AddRuleGroupListener $listener): void
    {
        $this->addGroupListener = $listener;
    }

    public function setRemoveRuleGroupFromAllEventsListener(RemoveGroupFromAllEventsListener $listener): void
    {
        $this->removeGroupFromAllEventsListener = $listener;
    }
}
