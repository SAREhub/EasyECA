<?php

namespace SAREhub\EasyECA\DI\Hoa\Rule\Asserter;

use Hoa\Ruler\Ruler;
use Hoa\Ruler\Visitor\Asserter;
use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\EasyECA\Hoa\Rule\Asserter\BasicRuleOperators;
use SAREhub\EasyECA\Hoa\Rule\Asserter\HoaRuleAsserter;

class HoaRuleAsserterProvider extends InvokableProvider
{
    public function get()
    {
        $asserter = new Asserter();
        $basicOperators = new BasicRuleOperators();
        $basicOperators->registerInAsserter($asserter);
        $ruler = new Ruler();
        $ruler->setAsserter($asserter);
        return new HoaRuleAsserter($ruler);
    }
}
