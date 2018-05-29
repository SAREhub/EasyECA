<?php

namespace SAREhub\EasyECA\Rule\Asserter;

use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;

class ExchangeInBodyRuleAssertContextFactoryTest extends TestCase
{

    public function testCreate()
    {
        $factory = new ExchangeInBodyRuleAssertContextFactory("test");
        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody("test_in_body"));

        $current = $factory->create($exchange);

        $this->assertEquals(["test" => "test_in_body"], $current);
    }
}
