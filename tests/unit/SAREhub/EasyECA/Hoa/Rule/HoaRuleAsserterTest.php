<?php

namespace SAREhub\EasyECA\Hoa\Rule;

use Hoa\Ruler\Ruler;
use PHPUnit\Framework\TestCase;

class HoaRuleAsserterTest extends TestCase
{

    public function testAssertWhenConditionIsTrueThenReturnTrue()
    {
        $ruler = new Ruler();
        $asserter = new HoaRuleAsserter($ruler);
        $this->assertTrue($asserter->assert("a = 1", ["a" => 1]));
    }

    public function testAssertWhenConditionIsFalseThenReturnTrue()
    {
        $ruler = new Ruler();
        $asserter = new HoaRuleAsserter($ruler);
        $this->assertFalse($asserter->assert("a != 1", ["a" => 1]));
    }
}
