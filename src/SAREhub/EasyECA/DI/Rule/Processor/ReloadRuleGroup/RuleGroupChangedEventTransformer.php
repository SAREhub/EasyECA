<?php


namespace SAREhub\EasyECA\DI\Rule\Processor\ReloadRuleGroup;


use SAREhub\Client\Message\Exchange;
use SAREhub\EasyECA\Event\RuleGroupChangedEvent;

abstract class RuleGroupChangedEventTransformer
{
    public function __invoke(Exchange $exchange)
    {
        $inBody = $exchange->getInBody();
        $groupId = $this->extractGroupId($inBody);
        $rules = $this->extractRules($inBody);
        $event = new RuleGroupChangedEvent($groupId, $rules);
        $exchange->getIn()->setBody($event);
    }

    protected abstract function extractGroupId($inBody): string;

    protected abstract function extractRules($inBody): array;
}
