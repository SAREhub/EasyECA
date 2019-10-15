<?php


namespace SAREhub\EasyECA\DI\Rule\Processor\ReloadRuleGroup;

use SAREhub\Client\Message\Exchange;
use SAREhub\EasyECA\Event\RuleGroupChangedEvent;

class RuleGroupChangedEventTransformer
{
    /**
     * @var callable
     */
    private $groupIdExtractor;

    /**
     * @var callable
     */
    private $rulesExtractor;

    public function __construct(callable $groupIdExtractor, callable $rulesExtractor)
    {
        $this->groupIdExtractor = $groupIdExtractor;
        $this->rulesExtractor = $rulesExtractor;
    }

    public function __invoke(Exchange $exchange)
    {
        $inBody = $exchange->getInBody();
        $groupId = ($this->groupIdExtractor)($inBody);
        $rules = ($this->rulesExtractor)($inBody);
        $event = new RuleGroupChangedEvent($groupId, $rules);
        $exchange->getIn()->setBody($event);
    }
}
