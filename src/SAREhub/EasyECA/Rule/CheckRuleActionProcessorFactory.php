<?php

namespace SAREhub\EasyECA\Rule;

use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Action\ActionProcessorFactory;

class CheckRuleActionProcessorFactory implements ActionProcessorFactory
{

    /**
     * @var RuleDefinitionFactory
     */
    private $ruleDefinitionFactory;

    /**
     * @var RuleParser
     */
    private $ruleParser;

    public function __construct(RuleDefinitionFactory $ruleDefinitionFactory, RuleParser $ruleParser)
    {
        $this->ruleDefinitionFactory = $ruleDefinitionFactory;
        $this->ruleParser = $ruleParser;
    }

    public function create(ActionDefinition $actionDefinition): Processor
    {
        $rule = $this->ruleDefinitionFactory->create($actionDefinition->getParameter("rule"));
        return $this->ruleParser->parse($rule);
    }
}
