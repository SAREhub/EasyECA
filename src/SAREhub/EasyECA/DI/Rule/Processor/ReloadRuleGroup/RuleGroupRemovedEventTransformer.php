<?php


namespace SAREhub\EasyECA\DI\Rule\Processor\ReloadRuleGroup;


use SAREhub\Client\Message\Exchange;
use SAREhub\EasyECA\Event\RuleGroupRemovedEvent;

abstract class RuleGroupRemovedEventTransformer
{
    public function __invoke(Exchange $exchange)
    {
        $groupId = $this->extractGroupId($exchange->getInBody());
        $event = new RuleGroupRemovedEvent($groupId);
        $exchange->getIn()->setBody($event);
    }

    public abstract function extractGroupId($inBody): string;
}
