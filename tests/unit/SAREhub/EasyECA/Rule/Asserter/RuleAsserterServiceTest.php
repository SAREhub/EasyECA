<?php

namespace SAREhub\EasyECA\Rule\Asserter;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class RuleAsserterServiceTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | RuleAssertContextFactory
     */
    private $assertContextFactory;

    /**
     * @var MockInterface | RuleAsserter
     */
    private $asserter;

    /**
     * @var RuleAsserterService
     */
    private $service;

    protected function setUp()
    {
        $this->assertContextFactory = \Mockery::mock(RuleAssertContextFactory::class);
        $this->asserter = \Mockery::mock(RuleAsserter::class);
        $this->service = new RuleAsserterService($this->assertContextFactory, $this->asserter);
    }

    public function testAssertThenCallAssertContextFactoryCreate()
    {
        $data = "test_data";
        $this->assertContextFactory->expects("create")->withArgs([$data])->andReturn([]);
        $this->asserter->shouldIgnoreMissing(false);
        $this->service->assert("test_condition", $data);
    }

    public function testAssertThenCallAsserterAssert()
    {
        $data = "test_data";
        $context = ["test_context"];
        $this->assertContextFactory->expects("create")->withArgs([$data])->andReturn($context);

        $condition = "test_condition";
        $this->asserter->expects("assert")->withArgs([$condition, $context]);
        $this->service->assert($condition, $data);
    }

    public function testAssertThenReturnAsserterAssertValue()
    {
        $this->assertContextFactory->expects("create")->andReturn([]);
        $this->asserter->expects("assert")->andReturn(true);
        $this->assertTrue($this->service->assert("", ""));
    }
}
