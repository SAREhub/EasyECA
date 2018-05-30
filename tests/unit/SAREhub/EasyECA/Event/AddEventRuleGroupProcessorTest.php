<?php

namespace SAREhub\EasyECA\Event;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;

class AddEventRuleGroupProcessorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | EventRuleGroupManager
     */
    private $manager;

    /**
     * @var AddEventRuleGroupProcessor
     */
    private $processor;

    public function setUp()
    {
        $this->manager = \Mockery::mock(EventRuleGroupManager::class);
        $this->processor = new AddEventRuleGroupProcessor($this->manager);
    }

    public function testProcess()
    {
        $eventRuleGroup = \Mockery::mock(EventRuleGroupDefinition::class);
        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody($eventRuleGroup));

        $this->manager->expects("add")->withArgs([$eventRuleGroup]);
        $this->processor->process($exchange);
    }
}
