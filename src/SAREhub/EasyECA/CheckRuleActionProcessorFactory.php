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

    /**
     * @var RuleDefinitionFactory
     */
    private $ruleDefinitionFactory;

    public function __construct(
        RuleDefinitionFactory $ruleDefinitionFactory,
        RuleAsserterService $asserterService,
        ActionParser $actionParser
    )
    {
        $this->ruleDefinitionFactory = $ruleDefinitionFactory;
        $this->asserterService = $asserterService;
        $this->actionParser = $actionParser;
    }

    public function create(ActionDefinition $actionDefinition): Processor
    {
        $rule = $this->ruleDefinitionFactory->create($actionDefinition->getParameter("rule"));
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