<?php

namespace SAREhub\EasyECA\Rule\Loader\Http;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;
use SAREhub\EasyECA\Util\HttpGetRequestCommand;

class HttpEventRuleGroupsLoaderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | HttpGetRequestCommand
     */
    private $command;

    /**
     * @var MockInterface | HttpResponseParsingStrategy
     */
    private $strategy;

    /**
     * @var HttpEventRuleGroupsLoader
     */
    private $loader;

    public function setUp()
    {
        $this->command = \Mockery::mock(HttpGetRequestCommand::class);
        $this->strategy = \Mockery::mock(HttpResponseParsingStrategy::class);
        $this->loader = new HttpEventRuleGroupsLoader($this->command, $this->strategy);
    }

    public function testLoad()
    {
        $expectedDefinitions = [
            \Mockery::mock(EventRuleGroupDefinition::class),
            \Mockery::mock(EventRuleGroupDefinition::class)
        ];

        $response = \Mockery::mock(ResponseInterface::class);
        $this->command->expects("execute")->andReturn($response);

        $this->strategy->expects("parse")->withArgs([$response])->andReturn($expectedDefinitions);

        $this->assertEquals($expectedDefinitions, $this->loader->load());
    }
}
