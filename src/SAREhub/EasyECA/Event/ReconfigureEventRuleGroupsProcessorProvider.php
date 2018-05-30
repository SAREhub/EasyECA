<?php


namespace SAREhub\EasyECA\Event;


use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;
use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;

class ReconfigureEventRuleGroupsProcessorProvider extends InvokableProvider
{
    /**
     * @var EventRuleGroupManager
     */
    private $manager;

    private $eventTypeRulesGroupingStrategy;

    public function __construct(EventRuleGroupManager $manager, $eventTypeRulesGroupingStrategy)
    {
        $this->manager = $manager;
        $this->eventTypeRulesGroupingStrategy = $eventTypeRulesGroupingStrategy;
    }

    public function get(): Processor
    {
        return Processors::pipeline()->addAll([
            new RemoveRuleGroupProcessor($this->manager),
            Processors::filter(function (Exchange $exchange) {
                return $exchange->getIn()->getBody() instanceof RuleGroupChangedEvent;
            })->to(),
            new AddEventRuleGroupProcessor($this->manager)
        ]);
    }
}