<?php

namespace SAREhub\EasyECA\Action;

class ActionDefinition
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

    public static function createFromArray(array $data)
    {
        return new self($data['action'], isset($data['parameters']) ? $data['parameters'] : []);
    }

    public function getAction(): string
    {
        return $this->action;
    }


    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function toArray(): array
    {
        return [
            'action' => $this->getAction(),
            'parameters' => $this->getParameters()
        ];
    }
}