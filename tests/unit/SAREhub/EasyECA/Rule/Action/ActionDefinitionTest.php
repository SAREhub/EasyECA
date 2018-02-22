<?php

namespace SAREhub\EasyECA\Rule\Action;

use PHPUnit\Framework\TestCase;

class ActionDefinitionTest extends TestCase
{

    public function testGetParameterWhenExistsThenReturn()
    {
        $definition = new ActionDefinition("test", ["test_parameter" => "test_value"]);
        $this->assertEquals("test_value", $definition->getParameter("test_parameter"));
    }

    public function testGetParameterWhenNotExistsThenThrowException()
    {
        $definition = new ActionDefinition("test", []);
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage("Action parameter: 'test_parameter' not found");
        $definition->getParameter("test_parameter");
    }

    public function testHasParameterWhenExists()
    {
        $definition = new ActionDefinition("test", ["test_parameter" => "test_value"]);
        $this->assertTrue($definition->hasParameter("test_parameter"));
    }

    public function testHasParameterWhenNotExists()
    {
        $definition = new ActionDefinition("test", []);
        $this->assertFalse($definition->hasParameter("test_parameter"));
    }
}
