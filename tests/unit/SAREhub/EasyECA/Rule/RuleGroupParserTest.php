<?php

namespace SAREhub\EasyECA\Rule;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\EasyECA\Rule\Definition\RuleDefinition;
use SAREhub\EasyECA\Rule\Definition\RuleGroupDefinition;

class RuleGroupParserTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /**
     * @var MockInterface | RuleParser
     */
    private $ruleParser;

    /**
     * @var RuleGroupParser
     */
    private $parser;

    protected function setUp()
    {
        $this->ruleParser = \Mockery::mock(RuleParser::class);
        $this->parser = new RuleGroupParser($this->ruleParser);
    }

    public function testParse()
    {
        $ruleDefinition = new RuleDefinition("");
        $groupDefinition = new RuleGroupDefinition("test_group", [$ruleDefinition]);

        $ruleProcessor = \Mockery::mock(CheckRuleProcessor::class);
        $this->ruleParser->expects("parse")->withArgs([$ruleDefinition])->andReturn($ruleProcessor);
        $processor = $this->parser->parse($groupDefinition);
        $this->assertSame($ruleProcessor, $processor->getProcessors()[0]);
    }
}
