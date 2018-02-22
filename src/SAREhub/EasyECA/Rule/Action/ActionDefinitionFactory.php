<?php


namespace SAREhub\EasyECA\Rule\Action;


class ActionDefinitionFactory
{
    public function create(array $data): ActionDefinition
    {
        if (empty($data)) {
            return ActionDefinition::createNopDefinition();
        }

        if (empty($data["action"])) {
            throw new \InvalidArgumentException("ActionDefinition data is invalid: empty action");
        }

        return new ActionDefinition($data["action"], $data["parameters"] ?? []);
    }
}