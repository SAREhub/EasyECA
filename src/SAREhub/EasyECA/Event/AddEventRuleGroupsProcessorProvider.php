<?php


namespace SAREhub\EasyECA\Event;


use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;
use SAREhub\Commons\Misc\ArrayHelper;
use SAREhub\Commons\Misc\InvokableProvider;

class AddEventRuleGroupsProcessorProvider extends InvokableProvider
{
    /**
     * @var callable
     */
    private $eventTypeRulesGroupingStrategy;

    public function __construct(callable $eventTypeRulesGroupingStrategy)
    {
        $this->eventTypeRulesGroupingStrategy = $eventTypeRulesGroupingStrategy;
    }

    public function get(): Processor
    {
        return Processors::pipeline()->addAll([
            Processors::transform(function (Exchange $exchange) {
                /** @var RuleGroupChangedEvent $event */
                $event = $exchange->getIn()->getBody();
                $groupedRules = ArrayHelper::groupBy($event->getRules(), $this->eventTypeRulesGroupingStrategy);
                $exchange->getOut()->setBody($groupedRules);
            })
        ]);
    }
}