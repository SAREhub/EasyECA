<?php

namespace SAREhub\Example\SimpleRuleProcessing;


use SAREhub\Client\Processor\Processor;
use SAREhub\EasyECA\Rule\Action\ActionDefinition;
use SAREhub\EasyECA\Rule\Action\ActionProcessorFactory;

class EchoActionProcessorFactory implements ActionProcessorFactory
{
    public function create(ActionDefinition $actionDefinition): Processor
    {
        $message = $actionDefinition->getParameter("message");
        return new EchoActionProcessor($message);
    }
}