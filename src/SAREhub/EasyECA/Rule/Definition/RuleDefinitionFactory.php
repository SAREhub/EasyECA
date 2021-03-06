<?php

namespace SAREhub\EasyECA\Rule\Definition;

use SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory;

class RuleDefinitionFactory
{
    /**
     * @var ActionDefinitionFactory
     */
    private $actionDefinitionFactory;

    public function __construct(ActionDefinitionFactory $actionDefinitionFactory)
    {
        $this->actionDefinitionFactory = $actionDefinitionFactory;
    }

    public function create(array $data): RuleDefinition
    {
        $this->assertData($data);
        $condition = $data["condition"];
        $onPass = $this->actionDefinitionFactory->create($data["onPass"] ?? []);
        $onFail = $this->actionDefinitionFactory->create($data["onFail"] ?? []);
        return new RuleDefinition($condition, $onPass, $onFail);
    }

    private function assertData(array $data)
    {
        if (empty($data["condition"])) {
            throw  new \InvalidArgumentException("RuleDefinition data is invalid: empty condition");
        }

        if (empty($data["onPass"]) && empty($data["onFail"])) {
            throw new \InvalidArgumentException("RuleDefinition data is invalid: onPass or onFail must be defined");
        }
    }
}
