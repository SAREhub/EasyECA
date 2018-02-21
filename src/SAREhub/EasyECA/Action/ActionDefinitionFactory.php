<?php


namespace SAREhub\EasyECA\Action;


class ActionDefinitionFactory
{
    public function create(array $data): ActionDefinition
    {
        if (empty($data)) {
            return ActionDefinition::createNopDefinition();
        }

        if (empty($data["action"])) {
            throw new \InvalidArgumentException("ActionDefinition data needs 'action' value");
        }

        return new ActionDefinition($data["action"], $data["parameters"] ?? []);
    }
}