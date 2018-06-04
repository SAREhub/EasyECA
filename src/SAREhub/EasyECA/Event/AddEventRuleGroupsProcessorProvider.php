<?php

namespace SAREhub\EasyECA\Event;

use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;
use SAREhub\Client\Processor\SplittingStrategy;
use SAREhub\Commons\Misc\InvokableProvider;

class AddEventRuleGroupsProcessorProvider extends InvokableProvider
{

    /**
     * @var SplittingStrategy
     */
    private $eventTypeRulesSplittingStrategy;

    /**
     * @var Processor
     */
    private $addEventRuleGroup;

    /**
     * @param SplittingStrategy $eventTypeRulesSplittingStrategy
     * @param Processor $addEventRuleGroup
     */
    public function __construct(SplittingStrategy $eventTypeRulesSplittingStrategy, Processor $addEventRuleGroup)
    {
        $this->eventTypeRulesSplittingStrategy = $eventTypeRulesSplittingStrategy;
        $this->addEventRuleGroup = $addEventRuleGroup;
    }

    public function get(): Processor
    {
        return Processors::split($this->eventTypeRulesSplittingStrategy, $this->addEventRuleGroup);
    }
}
