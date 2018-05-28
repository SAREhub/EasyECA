<?php

namespace SAREhub\EasyECA\Hoa\Rule\Asserter;

use Hoa\Ruler\Context;
use Hoa\Ruler\Ruler;
use SAREhub\EasyECA\Rule\Asserter\RuleAsserter;
use SAREhub\EasyECA\Rule\Asserter\RuleAssertException;

class HoaRuleAsserter implements RuleAsserter
{
    /**
     * @var Ruler
     */
    private $ruler;

    public function __construct(Ruler $ruler)
    {
        $this->ruler = $ruler;
    }

    /**
     * @param mixed $condition
     * @param array $context
     * @return bool
     * @throws \SAREhub\EasyECA\Rule\Asserter\RuleAssertException
     */
    public function assert($condition, array $context): bool
    {
        try {
            return $this->ruler->assert($condition, new Context($context));
        } catch (\Exception $e) {
            throw new \SAREhub\EasyECA\Rule\Asserter\RuleAssertException("Hoa assert exception occurred", 500, $e);
        }
    }
}
