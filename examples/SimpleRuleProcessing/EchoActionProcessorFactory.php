<?php

namespace SAREhub\Example\SimpleRuleProcessing;


use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Action\ActionProcessorFactory;

class EchoActionProcessorFactory implements ActionProcessorFactory
{
    const PARAM_MESSAGE = "message";

    public function create(ActionDefinition $actionDefinition): Processor
    {
        $message = $actionDefinition->getParameter(self::PARAM_MESSAGE);
        return new EchoActionProcessor($message);
    }
}