<?php

namespace SAREhub\EasyECA\Rule;

interface RuleAsserter
{
    /**
     * Checks rule condition in given context
     * @param mixed $condition
     * @param array $context
     * @return bool
     */
    public function assert($condition, array $context): bool;

}