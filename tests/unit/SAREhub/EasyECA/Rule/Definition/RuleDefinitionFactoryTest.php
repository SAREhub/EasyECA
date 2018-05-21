<?php

namespace SAREhub\EasyECA\Rule\Definition;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory;

class RuleDefinitionFactoryTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | \SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory
     */
    private $actionDefinitionFactory;

    /**
     * @var RuleDefinitionFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->actionDefinitionFactory = \Mockery::mock(ActionDefinitionFactory::class)
            ->shouldIgnoreMissing(ActionDefinition::createNopDefinition());

        $this->factory = new RuleDefinitionFactory($this->actionDefinitionFactory);
    }

    public function testCreateThenHasConditionFromData()
    {
        $data = [
            "condition" => "test_condition",
            "onPass" => ["test_onPass"]
        ];

        $definition = $this->factory->create($data);

        $this->assertEquals("test_condition", $definition->getCondition());
    }

    public function testCreateWhenConditionIsNotSetsThenThrowException()
    {
        $data = [];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("RuleDefinition data is invalid: empty condition");

        $this->factory->create($data);
    }

    public function testCreateWhenOnPassSetsThenHasOnPass()
    {
        $data = [
            "condition" => "test_condition",
            "onPass" => ["test_onPass"]
        ];

        $expectedActionDefinition = new ActionDefinition("test");
        $this->actionDefinitionFactory->expects("create")->withArgs([$data["onPass"]])->andReturn($expectedActionDefinition);
        $definition = $this->factory->create($data);

        $this->assertSame($expectedActionDefinition, $definition->getOnPass());
    }

    public function testCreateWhenOnFailSetsThenHasOnPass()
    {
        $data = [
            "condition" => "test_condition",
            "onFail" => ["test_onFail"]
        ];

        $expectedActionDefinition = new ActionDefinition("test");
        $this->actionDefinitionFactory->expects("create")->withArgs([$data["onFail"]])->andReturn($expectedActionDefinition);
        $definition = $this->factory->create($data);

        $this->assertSame($expectedActionDefinition, $definition->getOnFail());
    }

    public function testCreateWhenOnPassAndOnFailIsNotSetsThenThrowException()
    {
        $data = [
            "condition" => "test_condition"
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("RuleDefinition data is invalid: onPass or onFail must be defined");
        $this->factory->create($data);
    }
}
