<?php

namespace SAREhub\EasyECA\Rule\Action;

use OutOfBoundsException;

class ActionDefinition implements \JsonSerializable
{

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $parameters;

    public function __construct(string $action, array $parameters = [])
    {
        $this->action = $action;
        $this->parameters = $parameters;
    }

    public static function createNopDefinition(): self
    {
        return new ActionDefinition("nop");
    }

    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws OutOfBoundsException When parameter not found
     */
    public function getParameter(string $name)
    {
        if ($this->hasParameter($name)) {
            return $this->parameters[$name];
        }

        throw new OutOfBoundsException("Action parameter: '$name' not found");
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'action' => $this->getAction(),
            'parameters' => $this->getParameters()
        ];
    }
}
