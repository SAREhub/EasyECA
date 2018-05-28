<?php

namespace SAREhub\EasyECA\Util;

use Hamcrest\Matchers;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;

class MulticastGroupsRouterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MulticastGroupsRouter
     */
    private $router;

    protected function setUp()
    {
        $this->router = new MulticastGroupsRouter(function (Exchange $exchange) {
            return $exchange->getIn()->getBody();
        });
    }

    public function testAddWhenEmptyRoute()
    {
        $processor = $this->createProcessor();
        $this->router->add("test_key", "test_member", $processor);
        $this->assertEquals(["test_member" => $processor], $this->router->getRouteMembers("test_key"));
    }

    public function testAddWhenHasRoute()
    {
        $member1 = $this->createProcessor();
        $this->router->add("test_key", "test_member_1", $member1);
        $member2 = $this->createProcessor();
        $this->router->add("test_key", "test_member_2", $member2);

        $expectedMembers = ["test_member_1" => $member1, "test_member_2" => $member2];
        $this->assertEquals($expectedMembers, $this->router->getRouteMembers("test_key"));
    }

    public function testRemoveFromAll()
    {
        $this->router->add("test_key_1", "test_member_1", $this->createProcessor());
        $this->router->add("test_key_2", "test_member_1", $this->createProcessor());
        $this->router->add("test_key_1", "test_member_2", $this->createProcessor());
        $this->router->add("test_key_2", "test_member_2", $this->createProcessor());

        $this->router->removeFromAll("test_member_1");

        $this->assertArrayNotHasKey("test_member_1", $this->router->getRouteMembers("test_key_1"));
        $this->assertArrayNotHasKey("test_member_1", $this->router->getRouteMembers("test_key_2"));
    }

    public function testRemoveFromAllWhenIsLastInRoute()
    {
        $this->router->add("test_key_1", "test_member_1", $this->createProcessor());

        $this->router->removeFromAll("test_member_1");

        $this->assertFalse($this->router->hasRoute("test_key_1"));
    }

    public function testProcessWhenHasRoute()
    {
        $processor = $this->createProcessor();
        $this->router->add("test_key_1", "test_member_1", $processor);

        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody("test_key_1"));
        $processor->expects("process")->withArgs([Matchers::equalTo($exchange)]);

        $this->router->process($exchange);
    }

    public function testProcessWhenHasNotRoute()
    {
        $processor = $this->createProcessor();
        $this->router->add("test_key_1", "test_member_1", $processor);

        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody("test_key_other"));
        $processor->expects("process")->never();

        $this->router->process($exchange);
    }

    /**
     * @return MockInterface | Processor
     */
    private function createProcessor(): Processor
    {
        return \Mockery::mock(Processor::class);
    }
}
