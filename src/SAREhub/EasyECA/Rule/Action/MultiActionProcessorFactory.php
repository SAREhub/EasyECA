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

    public function create(ActionDefinition $actionDefinition): Processor
    {
        $pipeline = Processors::pipeline();
        foreach ($actionDefinition->getParameter(self::ACTIONS_PARAMETER) as $action) {
            $pipeline->add($this->parseAction($action));
        }

        return $pipeline;
    }

    private function parseAction(array $action): Processor
    {
        $actionDefinition = $this->actionDefinitionFactory->create($action);
        return $this->actionParser->parse($actionDefinition);
    }
}
