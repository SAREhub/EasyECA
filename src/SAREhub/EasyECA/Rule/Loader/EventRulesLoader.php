<?php

namespace SAREhub\EasyECA\Rule\Loader;


use SAREhub\EasyECA\Rule\Definition\EventRuleGroupsDefinition;

interface EventRulesLoader
{
    /**
     * @return EventRuleGroupsDefinition[]
     */
    public function load(): array;
}