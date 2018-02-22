<?php

namespace SAREhub\EasyECA\Rule\Action;


use SAREhub\Client\Processor\NullProcessor;
use SAREhub\Client\Processor\Processor;

class NopActionProcessorFactory implements ActionProcessorFactory
{

    public function create(ActionDefinition $actionDefinition): Processor
    {
        return new NullProcessor();
    }
}