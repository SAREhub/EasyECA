<?php

namespace SAREhub\EasyECA\Rule;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Rule\Asserter\RuleAsserterService;

class CheckRuleProcessorTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var RuleAsserterService | MockInterface
     */
    private $asserterService;

    /**
     * @var MockInterface | Processor
     */
    private $onPass;

    /**
     * @var MockInterface | Processor
     */
    private $onFail;

    /**
     * @var CheckRuleProcessor
     */
    private $processor;

    protected function setUp()
    {
        $this->asserterService = \Mockery::mock(RuleAsserterService::class);
        $this->onPass = $this->createProcessor();
        $this->onFail = $this->createProcessor();

        $this->processor = new CheckRuleProcessor(
            $this->asserterService,
            "test_condition",
            $this->onPass,
            $this->onFail
        );
    }

    public function testProcessWhenAssertPassed()
    {
        $exchange = new BasicExchange();
        $this->asserterService->expects("assert")->withArgs(["test_condition", $exchange])->andReturn(true);
        $this->onPass->expects("process")->withArgs([$exchange]);
        $this->onFail->expects("process")->never();
        $this->processor->process($exchange);
    }

    public function testProcessWhenAssertNotPassed()
    {
        $exchange = new BasicExchange();
        $this->asserterService->expects("assert")->withArgs(["test_condition", $exchange])->andReturn(false);
        $this->onPass->expects("process")->never();
        $this->onFail->expects("process")->withArgs([$exchange]);
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
