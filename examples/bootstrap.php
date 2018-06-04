<?php

use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;

require dirname(__DIR__) . "/vendor/autoload.php";

function createEventExchange(string $eventType, $propertyValue = 1)
{
    return $exchange = BasicExchange::withIn(
        BasicMessage::withBody((object)["type" => $eventType, "property" => $propertyValue])
    );
}