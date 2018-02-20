<?php

namespace SAREhub\EasyECA;

use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\NullProcessor;
use SAREhub\EasyECA\Action\ActionDefinition;

class NopActionProcessorFactoryTest extends TestCase
{

    public function testCreate()
    {
        $factory = new NopActionProcessorFactory();
        $this->assertInstanceOf(NullProcessor::class, $factory->create(new ActionDefinition("nop")));
    }
}
