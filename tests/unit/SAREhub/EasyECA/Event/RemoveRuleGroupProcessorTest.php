<?php

namespace SAREhub\EasyECA\Event;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;

class RemoveRuleGroupProcessorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | EventRuleGroupManager
     */
    private $manager;

    /**
     * @var RemoveRuleGroupProcessor
     */
    private $processor;

    public function setUp()
    {
        $this->manager = \Mockery::mock(EventRuleGroupManager::class);
        $this->processor = new RemoveRuleGroupProcessor($this->manager);
    }

    public function testProcess()
    {
        $event = new RuleGroupRemovedEvent("test_group_id");
        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody($event));

        $this->manager->expects("removeGroupFromAllEvents")->withArgs(["test_group_id"]);

        $this->processor->process($exchange);
    }
}
