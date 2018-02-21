<?php

namespace SAREhub\EasyECA;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\Processors;
use SAREhub\EasyECA\Action\ActionDefinition;
use SAREhub\EasyECA\Action\ActionParser;
use SAREhub\EasyECA\Rule\RuleAsserterService;

class RuleParserTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | RuleAsserterService
     */
    private $asserterService;

    /**
     * @var MockInterface | ActionParser
     */
    private $actionParser;

    /**
     * @var RuleParser
     */
    private $parser;

    protected function setUp()
    {
        $this->asserterService = \Mockery::mock(RuleAsserterService::class);
        $this->actionParser = \Mockery::mock(ActionParser::class)->shouldIgnoreMissing(Processors::blackhole());

        $this->parser = new RuleParser($this->asserterService, $this->actionParser);
    }

    public function testParseThenReturnProcessor()
    {
        $ruleDefintion = new RuleDefinition("");

        $processor = $this->parser->parse($ruleDefintion);
        $this->assertInstanceOf(CheckRuleProcessor::class, $processor);
    }

    public function testParseThenCreatedProcessorHasAsserterService()
    {
        $ruleDefinition = new RuleDefinition("");

        /** @var CheckRuleProcessor $processor */
        $processor = $this->parser->parse($ruleDefinition);
        $this->assertSame($this->asserterService, $processor->getAsserterService());
    }

    public function testParseThenCreatedProcessorHasConditionFromAction()
    {
        $ruleDefinition = new RuleDefinition("test_condition", null, null);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->parser->parse($ruleDefinition);
        $this->assertEquals("test_condition", $processor->getCondition());
    }

    public function testParseThenCreatedProcessorHasOnPassFromAction()
    {
        $ruleDefinition = new RuleDefinition("", new ActionDefinition("onPass"), null);

        $expectedOnPass = Processors::blackhole();
        $this->actionParser->expects("parse")->withArgs([$ruleDefinition->getOnPass()])->andReturn($expectedOnPass);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->parser->parse($ruleDefinition);

        $this->assertSame($expectedOnPass, $processor->getOnPass());
    }

    public function testParseThenCreatedProcessorHasOnFailFromAction()
    {
        $ruleDefinition = new RuleDefinition("", null, new ActionDefinition("onFail"));
        $this->actionParser->expects("parse")->andReturn(Processors::blackhole());

        $expectedOnFail = Processors::blackhole();
        $this->actionParser->expects("parse")->withArgs([$ruleDefinition->getOnFail()])->andReturn($expectedOnFail);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->parser->parse($ruleDefinition);

        $this->assertSame($expectedOnFail, $processor->getOnFail());
    }
}
