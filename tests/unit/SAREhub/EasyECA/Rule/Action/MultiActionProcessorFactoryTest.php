<?php

namespace SAREhub\EasyECA\Rule\Action;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\NullProcessor;
use SAREhub\Client\Processor\Pipeline;
use SAREhub\Client\Processor\Processors;

class MultiActionProcessorFactoryTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | \SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory
     */
    private $actionDefinitionFactory;

    /**
     * @var MockInterface | \SAREhub\EasyECA\Rule\Action\ActionParser
     */
    private $actionParser;

    /**
     * @var \SAREhub\EasyECA\Rule\Action\ActionProcessorFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->actionParser = \Mockery::mock(ActionParser::class)
            ->shouldIgnoreMissing(Processors::blackhole());
        $this->actionDefinitionFactory = \Mockery::mock(ActionDefinitionFactory::class)
            ->shouldIgnoreMissing(new ActionDefinition("test"));
        $this->factory = new MultiActionProcessorFactory($this->actionParser, $this->actionDefinitionFactory);
    }

    public function testCreateThenReturnInstanceOfPipeline()
    {
        $processor = $this->factory->create($this->createActionDefinition());
        $this->assertInstanceOf(Pipeline::class, $processor);
    }

    public function testCreateThenCreatedProcessorHasActionProcessors()
    {
        $actionDefinition = $this->createActionDefinition();

        $subActionDefinition = new ActionDefinition("test_action");
        $this->actionDefinitionFactory->expects("create")
            ->withArgs([["test_action"]])
            ->andReturn($subActionDefinition);

        $subActionProcessor = new NullProcessor();
        $this->actionParser->expects("parse")->withArgs([$subActionDefinition])->andReturn($subActionProcessor);

        /** @var Pipeline $processor */
        $processor = $this->factory->create($actionDefinition);
        $this->assertEquals([$subActionProcessor], $processor->getProcessors());
    }

    private function createActionDefinition(): ActionDefinition
    {
        return new ActionDefinition("test", [
            "actions" => [
                ["test_action"]
            ]
        ]);
    }


}
