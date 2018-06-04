<?php

namespace SAREhub\EasyECA\Rule\Definition;

class RuleGroupDefinition implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var RuleDefinition[]
     */
    private $rules;

    /**
     * @param string $id
     * @param RuleDefinition[] $rules
     */
    public function __construct(string $id, array $rules)
    {
        $this->id = $id;
        $this->rules = $rules;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return RuleDefinition[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'rules' => $this->getRules()
        ];
    }
}
