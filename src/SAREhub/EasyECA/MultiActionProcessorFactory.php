<?php

namespace SAREhub\EasyECA;

use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;
use SAREhub\EasyECA\Action\ActionDefinition;
use SAREhub\EasyECA\Action\ActionParser;
use SAREhub\EasyECA\Action\ActionProcessorFactory;

class MultiActionProcessorFactory implements ActionProcessorFactory
{

    const ACTIONS_PARAMETER = "actions";
    /**
     * @var ActionParser
     */
    private $actionParser;

    public function __construct(ActionParser $actionParser)
    {
        $this->actionParser = $actionParser;
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
        return $this->actionParser->parse(ActionDefinition::createFromArray($action));
    }
}