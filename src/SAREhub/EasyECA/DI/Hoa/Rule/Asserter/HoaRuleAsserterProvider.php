<?php

namespace SAREhub\EasyECA\DI\Hoa\Rule\Asserter;

use Hoa\Ruler\Ruler;
use Hoa\Ruler\Visitor\Asserter;
use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\EasyECA\Hoa\Rule\Asserter\HoaRuleAsserter;
use SAREhub\EasyECA\Hoa\Rule\Asserter\RuleOperatorsInjector;

class HoaRuleAsserterProvider extends InvokableProvider
{

    /**
     * @var RuleOperatorsInjector[]
     */
    private $operatorsInjectors;

    public function __construct(array $operatorsInjectors)
    {
        $this->operatorsInjectors = $operatorsInjectors;
    }

    public function get()
    {
        $ruler = new Ruler();
        $ruler->setAsserter($this->createAsserter());
        return new HoaRuleAsserter($ruler);
    }

    private function createAsserter(): Asserter
    {
        $asserter = new Asserter();
        foreach ($this->operatorsInjectors as $injector) {
            $injector->injectToAsserter($asserter);
        }
        return $asserter;
    }
}
