<?php

namespace SAREhub\EasyECA\Rule;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Definition\RuleDefinition;
use SAREhub\EasyECA\Rule\Definition\RuleDefinitionFactory;

class CheckRuleActionProcessorFactoryTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | RuleDefinitionFactory
     */
    private $ruleDefinitionFactory;

    /**
     * @var MockInterface | RuleParser
     */
    private $ruleParser;

    /**
     * @var CheckRuleActionProcessorFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->ruleDefinitionFactory = \Mockery::mock(RuleDefinitionFactory::class)
            ->shouldIgnoreMissing(new RuleDefinition("", null, null));

        $this->ruleParser = \Mockery::mock(RuleParser::class)
            ->shouldIgnoreMissing(\Mockery::mock(CheckRuleProcessor::class));

        $this->factory = new CheckRuleActionProcessorFactory($this->ruleDefinitionFactory, $this->ruleParser);
    }

    public function testCreateThenRuleDefinitionFactoryCreate()
    {
        $actionDefinition = $this->createActionDefinition();

        $ruleDefinition = new RuleDefinition("");
        $this->ruleDefinitionFactory->expects("create")
            ->withArgs([$actionDefinition->getParameter("rule")])
            ->andReturn($ruleDefinition);

        $this->factory->create($actionDefinition);
    }

    public function testCreateThenReturnProcessor()
    {
        $actionDefinition = $this->createActionDefinition();

        $expectedProcessor = \Mockery::mock(CheckRuleProcessor::class);
        $this->ruleParser->expects("parse")->andReturn($expectedProcessor);

        $processor = $this->factory->create($actionDefinition);
        $this->assertSame($expectedProcessor, $processor);
    }

    private function createActionDefinition(): ActionDefinition
    {
        return new ActionDefinition("test", ["rule" => ["test_rule"]]);
    }
}
