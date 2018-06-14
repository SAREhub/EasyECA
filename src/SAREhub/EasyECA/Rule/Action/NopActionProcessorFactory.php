<?php

namespace SAREhub\EasyECA\Rule\Action;

use SAREhub\Client\Processor\NullProcessor;
use SAREhub\Client\Processor\Processor;

class NopActionProcessorFactory implements ActionProcessorFactory
{

    public function create(ActionDefinition $action): Processor
    {
        return new NullProcessor();
    }
}
