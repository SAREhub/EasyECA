<?php

namespace SAREhub\EasyECA\Rule;

use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Action\ActionParser;

class RuleParser
{
    /**
     * @var RuleAsserterService
     */
    private $asserterService;

    /**
     * @var ActionParser
     */
    private $actionParser;

    public function __construct(RuleAsserterService $asserterService, ActionParser $actionParser)
    {
        $this->asserterService = $asserterService;
        $this->actionParser = $actionParser;
    }

    public function parse(RuleDefinition $rule): CheckRuleProcessor
    {
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
