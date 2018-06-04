<?php

namespace SAREhub\EasyECA\Event;

use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;

class RemoveRuleGroupProcessor implements Processor
{
    /**
     * @var EventRuleGroupManager
     */
    private $ruleManager;

    public function __construct(EventRuleGroupManager $ruleManager)
    {
        $this->ruleManager = $ruleManager;
    }

    public function process(Exchange $exchange)
    {
        /** @var RuleGroupRemovedEvent $event */
        $event = $exchange->getIn()->getBody();
        $this->ruleManager->removeGroupFromAllEvents($event->getGroupId());
    }
}
