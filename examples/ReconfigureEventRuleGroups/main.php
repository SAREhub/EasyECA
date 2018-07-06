<?php

use Hoa\Ruler\Ruler;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processors;
use SAREhub\EasyECA\Event\AddEventRuleGroupProcessor;
use SAREhub\EasyECA\Event\AddEventRuleGroupsProcessorProvider;
use SAREhub\EasyECA\Event\DefaultEventTypeRuleGroupsSplittingStrategy;
use SAREhub\EasyECA\Event\ReconfigureRuleGroupProcessorProvider;
use SAREhub\EasyECA\Event\RemoveRuleGroupProcessor;
use SAREhub\EasyECA\Event\RuleGroupChangedEvent;
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

$eventRulesRouter = new MulticastGroupsRouter(function (Exchange $exchange) {
    return $exchange->getIn()->getBody()->type;
});
$eventRuleGroupManager = new EventRuleGroupManager($eventRulesRouter, $ruleGroupParser);

$splittingStrategy = new DefaultEventTypeRuleGroupsSplittingStrategy($eventRuleGroupDefinitionFactory);

$addEventRuleGroup = new AddEventRuleGroupProcessor($eventRuleGroupManager);
$addEventRuleGroupsProcessorProvider = new AddEventRuleGroupsProcessorProvider($splittingStrategy, $addEventRuleGroup);
$reconfigureProcessorProvider = new ReconfigureRuleGroupProcessorProvider(
    new RemoveRuleGroupProcessor($eventRuleGroupManager), $addEventRuleGroupsProcessorProvider->get());
$reconfigureProcessor = $reconfigureProcessorProvider->get();

$messageProcessor = Processors::router(function (Exchange $exchange) {
    return $exchange->getIn()->getBody()->type === "rule_group_changed" ? "rule_group_changed" : "other";
});
$messageProcessor->addRoute("rule_group_changed", Processors::pipeline()->addAll([
    Processors::transform(function (Exchange $exchange) {
        $inBody = $exchange->getIn()->getBody();
        $exchange->getIn()->setBody(new RuleGroupChangedEvent($inBody->groupId, $inBody->rules));
    }),
    $reconfigureProcessor
]));
$messageProcessor->addRoute("other", $eventRulesRouter);

$eventType1 = "event_type_1";
$eventType2 = "event_type_2";
$reconfigureMessage = (object)[
    "type" => "rule_group_changed",
    "groupId" => "my_group",
    "rules" => [
        [
            "event" => "$eventType1",
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
        ],
        [
            "event" => "$eventType2",
            "condition" => "event.property = 2",
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

$messageProcessor->process(BasicExchange::withIn(BasicMessage::withBody($reconfigureMessage)));

echo "\nProcessing event($eventType1) with property = 1:\n";
$messageProcessor->process(createEventExchange($eventType1, 1));

echo "\nProcessing event($eventType1) with property = 2:\n";
$messageProcessor->process(createEventExchange($eventType1, 2));

echo "\nProcessing event($eventType2) with property = 2:\n";
$messageProcessor->process(createEventExchange($eventType2, 2));

echo "\nProcessing event($eventType2) with property = 1:\n";
$messageProcessor->process(createEventExchange($eventType2, 1));