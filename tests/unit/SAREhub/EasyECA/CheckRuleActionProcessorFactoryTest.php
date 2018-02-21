<?php

namespace SAREhub\EasyECA;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\NullProcessor;
use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Action\ActionDefinition;
use SAREhub\EasyECA\Action\ActionParser;
use SAREhub\EasyECA\Rule\RuleAsserterService;

class CheckRuleActionProcessorFactoryTest extends TestCase
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
     * @var MockInterface | RuleDefinitionFactory
     */
    private $ruleDefinitionFactory;

    /**
     * @var CheckRuleActionProcessorFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->ruleDefinitionFactory = \Mockery::mock(RuleDefinitionFactory::class)
            ->shouldIgnoreMissing(new RuleDefinition("", null, null));

        $this->asserterService = \Mockery::mock(RuleAsserterService::class);
        $this->actionParser = \Mockery::mock(ActionParser::class)->shouldIgnoreMissing(new NullProcessor());
        $this->factory = new CheckRuleActionProcessorFactory(
            $this->ruleDefinitionFactory,
            $this->asserterService,
            $this->actionParser
        );
    }

    public function testCreateThenReturnProcessor()
    {
        $processor = $this->factory->create($this->createActionDefinition());
        $this->assertInstanceOf(CheckRuleProcessor::class, $processor);
    }

    public function testCreateThenCreatedProcessorHasAsserterService()
    {
        /** @var CheckRuleProcessor $processor */
        $processor = $this->factory->create($this->createActionDefinition());
        $this->assertSame($this->asserterService, $processor->getAsserterService());
    }

    public function testCreateThenCreatedProcessorHasConditionFromAction()
    {
        $actionDefinition = $this->createActionDefinition();
        $ruleDefinition = new RuleDefinition("test_condition", null, null);
        $this->ruleDefinitionFactory->expects("create")
            ->withArgs([$actionDefinition->getParameter("rule")])
            ->andReturn($ruleDefinition);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->factory->create($actionDefinition);
        $this->assertEquals("test_condition", $processor->getCondition());
    }

    public function testCreateThenCreatedProcessorHasOnPassFromAction()
    {
        $actionDefinition = $this->createActionDefinition();
        $onPass = \Mockery::mock(Processor::class);

        $ruleDefinition = new RuleDefinition("", new ActionDefinition("onPass"), null);
        $this->ruleDefinitionFactory->expects("create")->andReturn($ruleDefinition);
        $this->actionParser->expects("parse")->withArgs([$ruleDefinition->getOnPass()])->andReturn($onPass);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->factory->create($actionDefinition);

        $this->assertSame($onPass, $processor->getOnPass());
    }

    public function testCreateThenCreatedProcessorHasOnFailFromAction()
    {
        $actionDefinition = $this->createActionDefinition();
        $onFail = \Mockery::mock(Processor::class);

        $ruleDefinition = new RuleDefinition("", new ActionDefinition("onPass"), null);
        $this->ruleDefinitionFactory->expects("create")->andReturn($ruleDefinition);

        $this->actionParser->expects("parse")->andReturn(new NullProcessor());
        $this->actionParser->expects("parse")->withArgs([$ruleDefinition->getOnFail()])->andReturn($onFail);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->factory->create($actionDefinition);

        $this->assertSame($onFail, $processor->getOnFail());
    }

    private function createActionDefinition(): ActionDefinition
    {
        return new ActionDefinition("test", ["rule" => ["test_rule"]]);
    }
}
