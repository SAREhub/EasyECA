<?php

namespace SAREhub\EasyECA\Action;

use PHPUnit\Framework\TestCase;

class ActionDefinitionFactoryTest extends TestCase
{
    /**
     * @var ActionDefinitionFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new ActionDefinitionFactory();
    }

    public function testCreateThenDefinitionHasActionFromData()
    {
        $data = [
            "action" => "test_action",
            "parameters" => []
        ];
        $definition = $this->factory->create($data);
        $this->assertEquals("test_action", $definition->getAction());
    }

    public function testCreateWhenActionIsNotSetThenThrowException()
    {
        $data = [
            "parameters" => []
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ActionDefinition data needs 'action' value");
        $this->factory->create($data);
    }

    public function testCreateWhenActionIsEmptyThenThrowException()
    {
        $data = [
            "action" => "",
            "parameters" => []
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("ActionDefinition data needs 'action' value");
        $this->factory->create($data);
    }

    public function testCreateThenDefinitionHasParametersFromData()
    {
        $data = [
            "action" => "test",
            "parameters" => ["test_parameters"]
        ];
        $definition = $this->factory->create($data);
        $this->assertEquals(["test_parameters"], $definition->getParameters());
    }

    public function testCreateWhenParametersIsNotSetsThenParametersEmpty()
    {
        $data = [
            "action" => "test_action"
        ];

        $definition = $this->factory->create($data);
        $this->assertEmpty($definition->getParameters());
    }

    public function testCreateWhenDataEmptyThenReturnNopAction()
    {
        $data = [];
        $definition = $this->factory->create($data);
        $this->assertEquals("nop", $definition->getAction());
    }
}
