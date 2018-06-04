<?php

namespace SAREhub\EasyECA\Rule\Definition;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class RuleGroupDefinitionFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | RuleDefinitionFactory
     */
    private $ruleFactory;

    /**
     * @var RuleGroupDefinitionFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->ruleFactory = \Mockery::mock(RuleDefinitionFactory::class);
        $this->factory = new RuleGroupDefinitionFactory($this->ruleFactory);
    }

    public function testCreate()
    {
        $data = [
            "id" => "test_id",
            "rules" => [
                ["rule_1"]
            ]
        ];

        $expectedRuleDefinition = new RuleDefinition("test_condition");
        $this->ruleFactory->expects("create")->withArgs([["rule_1"]])->andReturn($expectedRuleDefinition);
        $definition = $this->factory->create($data);
        $this->assertEquals("test_id", $definition->getId());
        $this->assertEquals([$expectedRuleDefinition], $definition->getRules());
    }

    public function testCreateWhenEmptyId()
    {
        $data = [
            "rules" => [
                ["rule_1"]
            ]
        ];
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("RuleGroupDefinition data is invalid: empty id");
        $this->factory->create($data);
    }

    public function testCreateWhenEmptyRules()
    {
        $data = [
            "id" => "test_id",
            "rules" => [
            ]
        ];
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("RuleGroupDefinition data is invalid: empty rules");
        $this->factory->create($data);
    }

    public function testCreateWhenNotArrayRules()
    {
        $data = [
            "id" => "test_id",
            "rules" => "invalid"
        ];
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("RuleGroupDefinition data is invalid: rules isn't array");
        $this->factory->create($data);
    }

}
