<?php

namespace SAREhub\EasyECA\Rule\Loader\LoadStrategy;


use SAREhub\EasyECA\Rule\Definition\EventRuleGroupsDefinition;

interface EventLoadStrategy
{
    /**
     * @param array $data
     * @return EventRuleGroupsDefinition[]
     */
    public function load(array $data): array;
}