<?php


namespace SAREhub\EasyECA\Hoa\Rule;

use Hoa\Ruler\Context;
use Hoa\Ruler\Ruler;
use SAREhub\EasyECA\Rule\RuleAssertContextFactory;
use SAREhub\EasyECA\Rule\RuleAsserter;
use SAREhub\EasyECA\Rule\RuleAssertException;

class HoaRuleAsserter implements RuleAsserter
{
    /**
     * @var Ruler
     */
    private $ruler;

    /**
     * @var RuleAssertContextFactory
     */
    private $contextFactory;

    /**
     *
     * @param Ruler $ruler
     */
    public function __construct(Ruler $ruler)
    {
        $this->ruler = $ruler;
    }

    public function assert($condition, array $context): bool
    {
        try {
            return $this->ruler->assert($condition, new Context($context));
        } catch (\Exception $e) {
            throw new RuleAssertException("Hoa assert exception occurred", 500, $e);
        }

    }
}