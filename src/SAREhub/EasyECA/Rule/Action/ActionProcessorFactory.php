<?php

namespace SAREhub\EasyECA\Rule\Action;

use SAREhub\Client\Processor\Processor;

interface ActionProcessorFactory
{

    public function create(ActionDefinition $actionDefinition): Processor;
}
