<?php


namespace SAREhub\EasyECA\DI\Rule\Processor\ReloadRuleGroup;

use SAREhub\Client\Message\Exchange;
use SAREhub\EasyECA\Event\RuleGroupRemovedEvent;

class RuleGroupRemovedEventTransformer
{
    /**
     * @var callable
     */
    private $groupIdExtractor;

    public function __construct(callable $groupIdExtractor)
    {
        $this->groupIdExtractor = $groupIdExtractor;
    }

    public function __invoke(Exchange $exchange)
    {
        $groupId = ($this->groupIdExtractor)($exchange->getInBody());
        $event = new RuleGroupRemovedEvent($groupId);
        $exchange->getIn()->setBody($event);
    }
}
