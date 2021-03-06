<?php

use Hoa\Ruler\Ruler;
use SAREhub\Client\Message\Exchange;
use SAREhub\EasyECA\Hoa\Rule\Asserter\HoaRuleAsserter;
use SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory;
use SAREhub\EasyECA\Rule\Action\ActionParser;
use SAREhub\EasyECA\Rule\Asserter\ExchangeInBodyRuleAssertContextFactory;
use SAREhub\EasyECA\Rule\Asserter\RuleAsserterService;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinitionFactory;
use SAREhub\EasyECA\Rule\Definition\RuleDefinitionFactory;
use SAREhub\EasyECA\Rule\Definition\RuleGroupDefinitionFactory;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;
use SAREhub\EasyECA\Rule\RuleGroupParser;
use SAREhub\EasyECA\Rule\RuleParser;
use SAREhub\EasyECA\Util\MulticastGroupsRouter;
use SAREhub\Example\Util\EchoActionProcessorFactory;

require dirname(__DIR__) . "/bootstrap.php";

$eventType1 = "event_type_1";

$ruleGroupData1 = [
    "id" => "test_group",
    "rules" => [
        [
            "condition" => "event.property = 1",
            "onPass" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnPass action from $eventType1!"
                ],
            ],
            "onFail" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnFail action from $eventType1!"
                ],
            ]
        ]
    ]
];

$eventType2 = "event_type_2";

$ruleGroupData2 = [
    "id" => "test_group",
    "rules" => [
        [
            "condition" => "event.property = 1",
            "onPass" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnPass action from $eventType2!"
                ],
            ],
            "onFail" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnFail action from $eventType2!"
                ],
            ]
        ]
    ]
];

$actionDefinitionFactory = new ActionDefinitionFactory();
$ruleDefinitionFactory = new RuleDefinitionFactory($actionDefinitionFactory);
$ruleGroupDefinitionFactory = new RuleGroupDefinitionFactory($ruleDefinitionFactory);
$eventRuleGroupDefinitionFactory = new EventRuleGroupDefinitionFactory($ruleGroupDefinitionFactory);

$assertContextFactory = new ExchangeInBodyRuleAssertContextFactory("event");
$ruleAsserter = new HoaRuleAsserter(new Ruler());
$ruleAsserterService = new RuleAsserterService($assertContextFactory, $ruleAsserter);
$ruleParser = new RuleParser($ruleAsserterService, new ActionParser([
    "echo" => new EchoActionProcessorFactory()
]));
$ruleGroupParser = new RuleGroupParser($ruleParser);

$router = new MulticastGroupsRouter(function (Exchange $exchange) {
    return $exchange->getIn()->getBody()->type;
});
$eventRuleGroupManager = new EventRuleGroupManager($router, $ruleGroupParser);

$eventRuleGroupDefinition1 = $eventRuleGroupDefinitionFactory->create($eventType1, $ruleGroupData1);
$eventRuleGroupDefinition2 = $eventRuleGroupDefinitionFactory->create($eventType2, $ruleGroupData2);

$eventRuleGroupManager->add($eventRuleGroupDefinition1);
$eventRuleGroupManager->add($eventRuleGroupDefinition2);

echo "\nProcessing event($eventType1) with property = 1:\n";
$router->process(createEventExchange($eventType1, 1));

echo "\nProcessing event($eventType1) with property = 2:\n";
$router->process(createEventExchange($eventType1, 2));

echo "\nProcessing event($eventType2) with property = 1:\n";
$router->process(createEventExchange($eventType2, 1));

echo "\nProcessing event($eventType2) with property = 2:\n";
$router->process(createEventExchange($eventType2, 2));
