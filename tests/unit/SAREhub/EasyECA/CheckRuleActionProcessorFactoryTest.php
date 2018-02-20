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
     * @var CheckRuleActionProcessorFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->asserterService = \Mockery::mock(RuleAsserterService::class);
        $this->actionParser = \Mockery::mock(ActionParser::class)->shouldIgnoreMissing(new NullProcessor());
        $this->factory = new CheckRuleActionProcessorFactory($this->asserterService, $this->actionParser);
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
        /** @var CheckRuleProcessor $processor */
        $processor = $this->factory->create($this->createActionDefinition());
        $this->assertEquals("test_condition", $processor->getCondition());
    }

    public function testCreateThenCreatedProcessorHasOnPassFromAction()
    {
        $actionDefinition = $this->createActionDefinition();
        $onPass = \Mockery::mock(Processor::class);
        $this->actionParser->expects("parse")->withArgs(function (ActionDefinition $definition) {
            return $definition->getAction() == "test_onPass" && $definition->getParameters() == ["test_onPass"];
        })->andReturn($onPass);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->factory->create($actionDefinition);

        $this->assertSame($onPass, $processor->getOnPass());
    }

    public function testCreateThenCreatedProcessorHasOnFailFromAction()
    {
        $actionDefinition = $this->createActionDefinition();
        $onFail = \Mockery::mock(Processor::class);
        $this->actionParser->expects("parse")->andReturn(new NullProcessor());
        $this->actionParser->expects("parse")->withArgs(function (ActionDefinition $definition) {
            return $definition->getAction() == "test_onFail" && $definition->getParameters() == ["test_onFail"];
        })->andReturn($onFail);

        /** @var CheckRuleProcessor $processor */
        $processor = $this->factory->create($actionDefinition);

        $this->assertSame($onFail, $processor->getOnFail());
    }

    private function createActionDefinition(): ActionDefinition
    {
        return new ActionDefinition("test", ["rule" => [
            "condition" => "test_condition",
            "onPass" => [
                "action" => "test_onPass",
                "parameters" => ["test_onPass"]
            ],
            "onFail" => [
                "action" => "test_onFail",
                "parameters" => ["test_onFail"]
            ]
        ]]);
    }
}
