<?php

namespace SAREhub\EasyECA;


use SAREhub\Client\Processor\NullProcessor;
use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Action\ActionDefinition;
use SAREhub\EasyECA\Action\ActionProcessorFactory;

class NopActionProcessorFactory implements ActionProcessorFactory
{

    public function create(ActionDefinition $actionDefinition): Processor
    {
        return new NullProcessor();
    }
}