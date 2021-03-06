<?php

namespace SAREhub\EasyECA\Rule;

use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Action\ActionParser;
use SAREhub\EasyECA\Rule\Asserter\RuleAsserterService;
use SAREhub\EasyECA\Rule\Definition\RuleDefinition;

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
        $onPass = $this->createAction($rule->getOnPass());
        $onFail = $this->createAction($rule->getOnFail());
        return new CheckRuleProcessor($this->asserterService, $rule->getCondition(), $onPass, $onFail);
    }

    private function createAction(ActionDefinition $definition): Processor
    {
        return $this->actionParser->parse($definition);
    }
}

