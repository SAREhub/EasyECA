<?php

namespace SAREhub\EasyECA\Rule;

use SAREhub\EasyECA\Rule\Action\ActionDefinition;

class RuleDefinition implements \JsonSerializable
{
    /**
     * @var mixed
     */
    private $condition;

    /**
     * @var ActionDefinition
     */
    private $onPass;

    /**
     * @var ActionDefinition
     */
    private $onFail;

    public function __construct($condition, ?ActionDefinition $onPass = null, ?ActionDefinition $onFail = null)
    {
        $this->condition = $condition;
        $this->onPass = $onPass ?? ActionDefinition::createNopDefinition();
        $this->onFail = $onFail ?? ActionDefinition::createNopDefinition();
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function getOnPass(): ActionDefinition
    {
        return $this->onPass;
    }

    public function getOnFail(): ActionDefinition
    {
        return $this->onFail;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return [
            'condition' => $this->getCondition(),
            'onPass' => $this->getOnPass() ? $this->getOnPass()->toArray() : null,
            'onFail' => $this->getOnFail() ? $this->getOnFail()->toArray() : null
        ];
    }
}
