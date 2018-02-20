<?php

namespace SAREhub\EasyECA;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\NullProcessor;
use SAREhub\Client\Processor\Pipeline;
use SAREhub\Client\Processor\Processors;
use SAREhub\EasyECA\Action\ActionDefinition;
use SAREhub\EasyECA\Action\ActionParser;
use SAREhub\EasyECA\Action\ActionProcessorFactory;

class MultiActionProcessorFactoryTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | ActionParser
     */
    private $actionParser;

    /**
     * @var ActionProcessorFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->actionParser = \Mockery::mock(ActionParser::class)->shouldIgnoreMissing(Processors::blackhole());
        $this->factory = new MultiActionProcessorFactory($this->actionParser);
    }

    public function testCreateThenReturnInstanceOfPipeline()
    {
        $processor = $this->factory->create($this->createActionDefinition());
        $this->assertInstanceOf(Pipeline::class, $processor);
    }

    public function testCreateThenCreatedProcessorHasActionProcessors()
    {
        $subActionProcessor = new NullProcessor();
        $this->actionParser->expects("parse")->withArgs(function (ActionDefinition $definition) {
            return $definition->getAction() == "action_1";
        })->andReturn($subActionProcessor);

        /** @var Pipeline $processor */
        $processor = $this->factory->create($this->createActionDefinition());
        $this->assertEquals([$subActionProcessor], $processor->getProcessors());
    }

    private function createActionDefinition(): ActionDefinition
    {
        return new ActionDefinition("test", [
            MultiActionProcessorFactory::ACTIONS_PARAMETER => [["action" => "action_1"]]
        ]);
    }


}
