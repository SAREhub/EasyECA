<?php


namespace SAREhub\EasyECA\Rule;


use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;

interface AddRuleGroupListener
{
    public function onAddRuleGroup(EventRuleGroupDefinition $definition): void;
}
