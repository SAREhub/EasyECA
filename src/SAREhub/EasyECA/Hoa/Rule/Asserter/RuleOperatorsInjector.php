<?php


namespace SAREhub\EasyECA\Hoa\Rule\Asserter;

use Hoa\Ruler\Visitor\Asserter;

interface RuleOperatorsInjector
{
    public function injectToAsserter(Asserter $asserter): void;
}
