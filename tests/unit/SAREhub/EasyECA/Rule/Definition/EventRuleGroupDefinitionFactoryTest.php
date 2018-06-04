<?php

namespace SAREhub\EasyECA\Rule\Definition;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class EventRuleGroupDefinitionFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | RuleGroupDefinitionFactory
     */
    private $ruleGroupFactory;

    /**
     * @var EventRuleGroupDefinitionFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->ruleGroupFactory = \Mockery::mock(RuleGroupDefinitionFactory::class);
        $this->factory = new EventRuleGroupDefinitionFactory($this->ruleGroupFactory);
    }

    public function testCreate()
    {
        $data = ["rule_group_data"];

        $expectedRuleGroupDefinition = \Mockery::mock(RuleGroupDefinition::class);
        $this->ruleGroupFactory->expects("create")->withArgs([$data])->andReturn($expectedRuleGroupDefinition);

        $eventGroup = $this->factory->create("event", $data);
        $this->assertEquals("event", $eventGroup->getEventType());
        $this->assertEquals($expectedRuleGroupDefinition, $eventGroup->getRuleGroup());
    }
}
