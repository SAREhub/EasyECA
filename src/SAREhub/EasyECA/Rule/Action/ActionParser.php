<?php

namespace SAREhub\EasyECA\Rule\Action;


use SAREhub\Client\Processor\Processor;

class ActionParser
{
    /**
     * @var ActionProcessorFactory[]
     */
    private $factories;

    public function __construct(array $actionProcessorFactories)
    {
        $this->factories = $actionProcessorFactories;
    }

    /**
     * @param ActionDefinition $definition
     * @throws \InvalidArgumentException When factory not found
     * @return Processor
     */
    public function parse(ActionDefinition $definition): Processor
    {
        return $this->getFactory($definition->getAction())->create($definition);
    }

    /**
     * @param string $action
     * @throws \InvalidArgumentException When factory not found
     * @return ActionProcessorFactory
     */
    private function getFactory(string $action): ActionProcessorFactory
    {
        if (!$this->hasFactory($action)) {
            throw new \InvalidArgumentException("ActionProcessorFactory to action: '$action' not found");
        }

        return $this->factories[$action];
    }

    private function hasFactory(string $action): bool
    {
        return isset($this->factories[$action]);
    }
}
