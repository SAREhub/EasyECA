<?php

namespace SAREhub\EasyECA\Rule;

class RuleHelper
{
    public static function createRuleData(string $condition, array $onPass = null, array $onFail = null): array
    {
        return [
            "condition" => $condition,
            "onPass" => $onPass,
            "onFail" => $onFail
        ];
    }

    public static function createActionData(string $action, array $parameters): array
    {
        return [
            "action" => $action,
            "parameters" => $parameters,
        ];
    }
}