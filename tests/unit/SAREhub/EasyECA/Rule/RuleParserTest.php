<?php

namespace SAREhub\EasyECA\Rule;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Action\ActionParser;
use SAREhub\EasyECA\Rule\Definition\RuleDefinition;

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
        $ruleDefinition = new RuleDefinition("test_condition", new ActionDefinition("onPass"), new ActionDefinition("onFail"));
        $expectedOnPass = $this->actionParserExpectsParse($ruleDefinition->getOnPass());
        $expectedOnFail = $this->actionParserExpectsParse($ruleDefinition->getOnFail());

        $processor = $this->parser->parse($ruleDefinition);

        $this->assertInstanceOf(CheckRuleProcessor::class, $processor);
        $this->assertSame($this->asserterService, $processor->getAsserterService(), "asserterService");
        $this->assertEquals("test_condition", $processor->getCondition(), "condition");
        $this->assertSame($expectedOnPass, $processor->getOnPass(), "onPass");
        $this->assertSame($expectedOnFail, $processor->getOnFail(), "onFail");
    }

    private function actionParserExpectsParse(ActionDefinition $actionDefinition): Processor
    {
        $return = Processors::blackhole();
        $this->actionParser->expects("parse")->withArgs([$actionDefinition])->andReturn($return);
        return $return;
    }
}
