<?php

namespace SAREhub\EasyECA\Rule\Definition;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class EventRuleGroupsDefinitionFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | RuleGroupDefinitionFactory
     */
    private $ruleGroupFactory;

    /**
     * @var EventRuleGroupsDefinitionFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->ruleGroupFactory = \Mockery::mock(RuleGroupDefinitionFactory::class);
        $this->factory = new EventRuleGroupsDefinitionFactory($this->ruleGroupFactory);
    }

    public function testCreate()
    {
        $data = [
            [
                "id" => "test_id",
                "rules" => [
                    ["rule_1"]
                ]
            ]
        ];

        $expectedRuleGroupDefinition = \Mockery::mock(RuleGroupDefinition::class);
        $this->ruleGroupFactory->expects("create")->withArgs([$data[0]])->andReturn($expectedRuleGroupDefinition);

        $eventGroup = $this->factory->create("event", $data);
        $this->assertEquals("event", $eventGroup->getEventType());
        $this->assertEquals([$expectedRuleGroupDefinition], $eventGroup->getRuleGroups());
    }
}
