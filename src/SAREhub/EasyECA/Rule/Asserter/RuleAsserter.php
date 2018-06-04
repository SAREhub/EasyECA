<?php

namespace SAREhub\EasyECA\Rule\Asserter;

interface RuleAsserter
{
    /**
     * Checks rule condition with given data
     * @param mixed $condition
     * @param array $context
     * @return bool
     * @throws RuleAssertException
     */
    public function assert($condition, array $context): bool;
}

