<?php

namespace SAREhub\EasyECA\Rule;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupsDefinition;
use SAREhub\EasyECA\Rule\Definition\RuleGroupDefinition;

class RulesRegistryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var RulesRegistry
     */
    private $registry;

    public function setUp()
    {
        $this->registry = new RulesRegistry();
    }

    public function testAddWhenNotExistsThenAddGroupToRegistry()
    {
        /** @var EventRuleGroupsDefinition | MockInterface $eventsRuleGroup */
        $eventsRuleGroup = \Mockery::mock(EventRuleGroupsDefinition::class);
        $eventsRuleGroup->shouldReceive("getEventType")->andReturn("type_1");
        $this->registry->add($eventsRuleGroup);
        $this->assertEquals($eventsRuleGroup, $this->registry->getByEventType("type_1"));
    }

    public function testAddWhenExistsThenUpdateGroupInRegistry()
    {
        $ruleGroup = \Mockery::mock(RuleGroupDefinition::class);

        /** @var EventRuleGroupsDefinition | MockInterface $eventsRuleGroup */
        $eventsRuleGroup = \Mockery::mock(EventRuleGroupsDefinition::class);
        $eventsRuleGroup->shouldReceive("getEventType")->andReturn("type_1");
        $eventsRuleGroup->shouldReceive("getRuleGroups")->andReturn([$ruleGroup]);
        $this->registry->add($eventsRuleGroup);

        /** @var EventRuleGroupsDefinition | MockInterface $eventsRuleGroup2 */
        $eventsRuleGroup2 = \Mockery::mock(EventRuleGroupsDefinition::class);
        $eventsRuleGroup2->shouldReceive("getEventType")->andReturn("type_1");
        $eventsRuleGroup2->shouldReceive("getRuleGroups")->andReturn([$ruleGroup, $ruleGroup]);
        $this->registry->add($eventsRuleGroup2);

        $this->assertEquals($eventsRuleGroup2, $this->registry->getByEventType("type_1"));
        $this->assertEquals([$ruleGroup, $ruleGroup], $this->registry->getByEventType("type_1")->getRuleGroups());
    }
}
