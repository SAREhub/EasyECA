<?php

namespace SAREhub\EasyECA\Rule\Action;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\Processor;

class ActionParserTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    public function testParseWhenActionProcessorFactoryExistsThenReturnProcessor()
    {
        $processorFactory = \Mockery::mock(ActionProcessorFactory::class);
        $actionDefinition = new ActionDefinition("test_action");
        $parser = new ActionParser(["test_action" => $processorFactory]);

        $expectedProcessor = \Mockery::mock(Processor::class);
        $processorFactory->expects("create")->withArgs([$actionDefinition])->andReturn($expectedProcessor);
        $this->assertSame($expectedProcessor, $parser->parse($actionDefinition));
    }

    public function testAddActionFactory()
    {
        $processorFactory = \Mockery::mock(ActionProcessorFactory::class);
        $actionDefinition = new ActionDefinition("test_action");
        $parser = new ActionParser();
        $parser->addActionFactory("test_action", $processorFactory);

        $expectedProcessor = \Mockery::mock(Processor::class);
        $processorFactory->expects("create")->withArgs([$actionDefinition])->andReturn($expectedProcessor);
        $this->assertSame($expectedProcessor, $parser->parse($actionDefinition));
    }

    public function testParseWhenActionProcessorFactoryNotExistsThenThrowException()
    {
        $actionDefinition = new ActionDefinition("test_action");
        $parser = new ActionParser();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ActionProcessorFactory to action: 'test_action' not found");
        $parser->parse($actionDefinition);

    }
}
