<?php


namespace SAREhub\EasyECA\Hoa\Rule;


use Hoa\Ruler\Context;
use Hoa\Ruler\Ruler;
use SAREhub\EasyECA\Rule\RuleAsserter;

class HoaRuleAsserter implements RuleAsserter
{
    /**
     * @var Ruler
     */
    private $ruler;

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
        return $this->ruler->assert($condition, new Context($context));
    }
}