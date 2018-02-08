<?php

namespace SAREhub\EasyECA\Rule;

use SAREhub\Eca\RuleDefinition;

interface RuleAsserter
{
    /**
     * Checks rule condition in given context
     * @param RuleDefinition $rule
     * @param array $context
     * @return bool
     */
    public function assert(RuleDefinition $rule, array $context): bool;

}