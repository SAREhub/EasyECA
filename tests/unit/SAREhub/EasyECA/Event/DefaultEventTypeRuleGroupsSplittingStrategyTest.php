<?php

namespace SAREhub\EasyECA\Event;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinitionFactory;

class DefaultEventTypeRuleGroupsSplittingStrategyTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var EventRuleGroupDefinitionFactory | MockInterface
     */
    private $definitionFactory;

    /**
     * @var DefaultEventTypeRuleGroupsSplittingStrategy
     */
    private $strategy;

    protected function setUp()
    {
        $this->definitionFactory = \Mockery::mock(EventRuleGroupDefinitionFactory::class);
        $this->strategy = new DefaultEventTypeRuleGroupsSplittingStrategy($this->definitionFactory);
    }

    public function testSplit()
    {
        $rule1 = $this->createRuleData(1);
        $rule2 = $this->createRuleData(2);

        $message = BasicMessage::withBody(new RuleGroupChangedEvent("test_group_id", [$rule1, $rule2]));
        $definition1 = \Mockery::mock(EventRuleGroupDefinition::class);
        $this->definitionFactory->expects("create")
            ->with($rule1["event"], ["id" => "test_group_id", "rules" => [$rule1]])
            ->andReturn($definition1);
        $definition2 = \Mockery::mock(EventRuleGroupDefinition::class);
        $this->definitionFactory->expects("create")
            ->with($rule2["event"], ["id" => "test_group_id", "rules" => [$rule2]])
            ->andReturn($definition2);
        $splits = $this->strategy->split($message);
        $this->assertCount(2, $splits);
        $this->assertSame($definition1, $splits[0]->getIn()->getBody());
        $this->assertSame($definition2, $splits[1]->getIn()->getBody());

    }

    private function createRuleData(int $index)
    {
        return [
            "event" => "rule_${index}_event",
            "condition" => "rule_${index}"
        ];
    }
}
