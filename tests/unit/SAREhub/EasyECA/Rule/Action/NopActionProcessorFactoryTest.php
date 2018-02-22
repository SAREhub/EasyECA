<?php

namespace SAREhub\EasyECA\Rule\Action;

use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\NullProcessor;

class NopActionProcessorFactoryTest extends TestCase
{

    public function testCreate()
    {
        $factory = new NopActionProcessorFactory();
        $this->assertInstanceOf(NullProcessor::class, $factory->create(new ActionDefinition("nop")));
    }
}
