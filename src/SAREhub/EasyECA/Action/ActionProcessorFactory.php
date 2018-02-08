<?php

namespace SAREhub\Eca;

use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Action\ActionDefinition;

interface ActionProcessorFactory
{

    public function create(ActionDefinition $actionDefinition): Processor;
}