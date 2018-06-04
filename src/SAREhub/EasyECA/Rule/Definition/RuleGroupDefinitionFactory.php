<?php

namespace SAREhub\EasyECA\Rule\Definition;

class RuleGroupDefinitionFactory
{
    /**
     * @var RuleDefinitionFactory
     */
    private $ruleDefinitionFactory;

    public function __construct(RuleDefinitionFactory $ruleDefinitionFactory)
    {
        $this->ruleDefinitionFactory = $ruleDefinitionFactory;
    }

    /**
     * @param array $data
     * @return RuleGroupDefinition
     */
    public function create(array $data): RuleGroupDefinition
    {
        $this->assertData($data);

        $rules = [];
        foreach ($data["rules"] as $ruleData) {
            $rules[] = $this->ruleDefinitionFactory->create($ruleData);
        }
        return new RuleGroupDefinition($data["id"], $rules);
    }

    private function assertData(array $data): void
    {
        if (empty($data["id"])) {
            $this->throwInvalidDataException("empty id");
        }
        if (empty($data["rules"])) {
            $this->throwInvalidDataException("empty rules");
        }
        if (!is_array($data["rules"])) {
            $this->throwInvalidDataException("rules isn't array");
        }
    }

    private function throwInvalidDataException(string $message)
    {
        throw new \InvalidArgumentException("RuleGroupDefinition data is invalid: $message");
    }
}
