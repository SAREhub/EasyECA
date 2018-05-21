<?php


namespace SAREhub\EasyECA\Rule;


use SAREhub\Client\Processor\MulticastProcessor;
use SAREhub\Client\Processor\Processors;
use SAREhub\EasyECA\Rule\Definition\RuleGroupDefinition;

class RuleGroupParser
{
    /**
     * @var RuleParser
     */
    private $ruleParser;

    public function __construct(RuleParser $ruleParser)
    {
        $this->ruleParser = $ruleParser;
    }

    public function parse(RuleGroupDefinition $definition): MulticastProcessor
    {
        $processor = Processors::multicast();
        foreach ($definition->getRules() as $rule) {
            $processor->add($this->ruleParser->parse($rule));
        }
        return $processor;
    }
}