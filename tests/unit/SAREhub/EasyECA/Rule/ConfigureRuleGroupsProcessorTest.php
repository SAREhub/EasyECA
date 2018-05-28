<?php

namespace SAREhub\EasyECA\Rule;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\Client\Processor\Processor;

class ConfigureRuleGroupsProcessorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var Processor | MockInterface
     */
    private $ruleGroupChangedProcessor;

    /**
     * @var Processor | MockInterface
     */
    private $ruleGroupDeletedProcessor;

    /**
     * @var ConfigureRuleGroupsProcessor
     */
    private $processor;

    public function setUp()
    {
        $this->ruleGroupChangedProcessor = \Mockery::mock(Processor::class);

        $this->ruleGroupDeletedProcessor = \Mockery::mock(Processor::class);

        $this->processor = new ConfigureRuleGroupsProcessor($this->ruleGroupChangedProcessor,
            $this->ruleGroupDeletedProcessor);
    }

    public function testProcessWhenRuleGroupChangedEventIncome()
    {
        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody([
            "type" => ConfigureRuleGroupsProcessor::RULE_GROUP_CHANGED_ROUTE
        ]));

        $this->ruleGroupChangedProcessor->expects("process");
        $this->ruleGroupDeletedProcessor->expects("process")->never();

        $this->processor->process($exchange);
    }

    public function testProcessWhenRuleGroupDeletedEventIncome()
    {
        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody([
            "type" => ConfigureRuleGroupsProcessor::RULE_GROUP_DELETED_ROUTE
        ]));

        $this->ruleGroupChangedProcessor->expects("process")->never();
        $this->ruleGroupDeletedProcessor->expects("process");

        $this->processor->process($exchange);
    }
}
