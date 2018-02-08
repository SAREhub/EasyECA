<?php

namespace SAREhub\EasyECA\Action;

use SAREhub\Client\Processor\Processor;

interface ActionProcessorFactory
{

    public function create(ActionDefinition $actionDefinition): Processor;
}