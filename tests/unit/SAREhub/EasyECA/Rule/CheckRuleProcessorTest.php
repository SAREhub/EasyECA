<?php

namespace SAREhub\EasyECA\Rule;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Processor\Processor;

class CheckRuleProcessorTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var RuleAsserterService | MockInterface
     */
    private $asserterService;

    /**
     * @var CheckRuleProcessor
     */
    private $processor;

    protected function setUp()
    {
        $this->asserterService = \Mockery::mock(RuleAsserterService::class);
        $this->processor = new CheckRuleProcessor($this->asserterService, "test_condition");
    }

    public function testProcessThenCallAsserterServiceAssert()
    {
        $exchange = new BasicExchange();
        $this->asserterService->expects("assert")->withArgs([$this->processor->getCondition(), $exchange]);
        $this->processor->process($exchange);
    }

    public function testProcessWhenAssertPassedThenOnPassProcess()
    {
        $onPass = $this->createProcessor();
        $this->processor->setOnPass($onPass);
        $this->asserterService->expects("assert")->andReturn(true);

        $exchange = new BasicExchange();
        $onPass->expects("process")->withArgs([$exchange]);
        $this->processor->process($exchange);
    }

    public function testProcessWhenAssertPassedThenNotCallOnFailProcess()
    {
        $onFail = $this->createProcessor();
        $this->processor->setOnFail($onFail);
        $this->asserterService->expects("assert")->andReturn(true);

        $onFail->expects("process")->never();
        $this->processor->process(new BasicExchange());
    }

    public function testProcessWhenAssertNotPassedThenOnFailProcess()
    {
        $onFail = $this->createProcessor();
        $this->processor->setOnFail($onFail);
        $this->asserterService->expects("assert")->andReturn(false);

        $exchange = new BasicExchange();
        $onFail->expects("process")->withArgs([$exchange]);
        $this->processor->process($exchange);
    }

    /**
     * @return MockInterface | Processor
     */
    private function createProcessor()
    {
        return \Mockery::mock(Processor::class);
    }
}
