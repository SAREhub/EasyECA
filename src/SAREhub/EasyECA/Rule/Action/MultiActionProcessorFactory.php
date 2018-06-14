<?php

namespace SAREhub\EasyECA\Rule\Action;

use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;

class MultiActionProcessorFactory implements ActionProcessorFactory
{

    const ACTIONS_PARAMETER = "actions";

    /**
     * @var ActionDefinitionFactory
     */
    private $actionDefinitionFactory;

    /**
     * @var \SAREhub\EasyECA\Rule\Action\ActionParser
     */
    private $actionParser;

    public function __construct(ActionParser $actionParser, ActionDefinitionFactory $actionDefinitionFactory)
    {
        $this->actionParser = $actionParser;
        $this->actionDefinitionFactory = $actionDefinitionFactory;
    }

    public function create(ActionDefinition $action): Processor
    {
        $processor = Processors::multicast();
        foreach ($action->getParameter(self::ACTIONS_PARAMETER) as $action) {
            $processor->add($this->parseAction($action));
        }

        return $processor;
    }

    private function parseAction(array $action): Processor
    {
        $actionDefinition = $this->actionDefinitionFactory->create($action);
        return $this->actionParser->parse($actionDefinition);
    }
}
