<?php


namespace SAREhub\EasyECA\Event;


use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;
use SAREhub\Commons\Misc\InvokableProvider;

class ReconfigureRuleGroupProcessorProvider extends InvokableProvider
{
    /**
     * @var Processor
     */
    private $removeRuleGroup;

    /**
     * @var Processor
     */
    private $addRuleGroup;

    public function __construct(Processor $removeRuleGroup, Processor $addRuleGroup)
    {
        $this->removeRuleGroup = $removeRuleGroup;
        $this->addRuleGroup = $addRuleGroup;
    }

    public function get(): Processor
    {
        return Processors::pipeline()->addAll([
            $this->removeRuleGroup, // always remove rule group to clean state
            Processors::filter(function (Exchange $exchange) {
                return $exchange->getIn()->getBody() instanceof RuleGroupChangedEvent;
            })->to($this->addRuleGroup)
        ]);
    }
}
