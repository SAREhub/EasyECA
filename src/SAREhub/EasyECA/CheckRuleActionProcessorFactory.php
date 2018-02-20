<?php

namespace SAREhub\EasyECA;

use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Action\ActionDefinition;
use SAREhub\EasyECA\Action\ActionParser;
use SAREhub\EasyECA\Action\ActionProcessorFactory;
use SAREhub\EasyECA\Rule\RuleAsserterService;

class CheckRuleActionProcessorFactory implements ActionProcessorFactory
{

    /**
     * @var ActionParser
     */
    private $actionParser;

    /**
     * @var RuleAsserterService
     */
    private $asserterService;

    public function __construct(RuleAsserterService $asserterService, ActionParser $actionParser)
    {
        $this->asserterService = $asserterService;
        $this->actionParser = $actionParser;
    }

    public function create(ActionDefinition $actionDefinition): Processor
    {
        $rule = RuleDefinition::createFromArray($actionDefinition->getParameter("rule"));
        $processor = new CheckRuleProcessor($this->asserterService, $rule->getCondition());
        $processor->setOnPass($this->createAction($rule->getOnPass()));
        $processor->setOnFail($this->createAction($rule->getOnFail()));
        return $processor;
    }

    private function createAction(ActionDefinition $definition): Processor
    {
        return $this->actionParser->parse($definition);
    }


}