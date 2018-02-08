<?php

namespace SAREhub\EasyECA\Action;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\Processor;
use SAREhub\Eca\ActionProcessorFactory;

class ActionDefinitionParserTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    public function testParseWhenActionProcessorFactoryExistsThenReturnProcessor()
    {
        $processorFactory = \Mockery::mock(ActionProcessorFactory::class);

        $actionDefinition = new ActionDefinition("test_action");
        $parser = new ActionDefinitionParser(["test_action" => $processorFactory]);

        $expectedProcessor = \Mockery::mock(Processor::class);
        $processorFactory->expects("create")->withArgs([$actionDefinition])->andReturn($expectedProcessor);
        $this->assertSame($expectedProcessor, $parser->parse($actionDefinition));
    }

    public function testParseWhenActionProcessorFactoryNotExistsThenThrowException()
    {
        $actionDefinition = new ActionDefinition("test_action");
        $parser = new ActionDefinitionParser([]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ActionProcessorFactory to action: 'test_action' not found");
        $parser->parse($actionDefinition);

    }
}
