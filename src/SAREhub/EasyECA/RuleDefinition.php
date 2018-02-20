<?php

namespace SAREhub\EasyECA;

use SAREhub\EasyECA\Action\ActionDefinition;

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

    public function __construct($condition, ?ActionDefinition $onPass, ?ActionDefinition $onFail)
    {
        $this->condition = $condition;
        $this->onPass = $onPass ?? ActionDefinition::createNopDefinition();
        $this->onFail = $onFail ?? ActionDefinition::createNopDefinition();
    }

    /**
     * @param array $data
     * @return RuleDefinition
     */
    public static function createFromArray(array $data)
    {
        $onPass = isset($data['onPass']) ? ActionDefinition::createFromArray($data['onPass']) : null;
        $onFail = isset($data['onFail']) ? ActionDefinition::createFromArray($data['onFail']) : null;
        return new self($data["condition"], $onPass, $onFail);
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

    public function toArray()
    {
        return [
            'condition' => $this->getCondition(),
            'onPass' => $this->getOnPass() ? $this->getOnPass()->toArray() : null,
            'onFail' => $this->getOnFail() ? $this->getOnFail()->toArray() : null
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}