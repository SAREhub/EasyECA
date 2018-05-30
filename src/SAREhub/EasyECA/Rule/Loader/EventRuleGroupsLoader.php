<?php

namespace SAREhub\EasyECA\Rule\Loader;


use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;

interface EventRuleGroupsLoader
{
    /**
     * @return EventRuleGroupDefinition[]
     */
    public function load(): array;
}