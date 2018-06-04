<?php

use Hoa\Ruler\Ruler;
use SAREhub\EasyECA\Hoa\Rule\Asserter\HoaRuleAsserter;
use SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory;
use SAREhub\EasyECA\Rule\Action\ActionParser;
use SAREhub\EasyECA\Rule\Asserter\ExchangeInBodyRuleAssertContextFactory;
use SAREhub\EasyECA\Rule\Asserter\RuleAsserterService;
use SAREhub\EasyECA\Rule\Definition\RuleDefinitionFactory;
use SAREhub\EasyECA\Rule\RuleParser;
use SAREhub\Example\Util\EchoActionProcessorFactory;

require dirname(__DIR__) . "/bootstrap.php";

$actionDefinitionFactory = new ActionDefinitionFactory();
$ruleDefinitionFactory = new RuleDefinitionFactory($actionDefinitionFactory);

$ruleAsserterService = new RuleAsserterService(
    new ExchangeInBodyRuleAssertContextFactory("event"),
    new HoaRuleAsserter(new Ruler())
);
$ruleParser = new RuleParser($ruleAsserterService, new ActionParser([
    "echo" => new EchoActionProcessorFactory()
]));

$ruleData = [
    "condition" => "event.property = 1",
    "onPass" => [
        "action" => "echo",
        "parameters" => [
            "message" => "Im in OnPass action!"
        ],
    ],
    "onFail" => [
        "action" => "echo",
        "parameters" => [
            "message" => "Im in OnFail action!"
        ],
    ]
];
$ruleDefinition = $ruleDefinitionFactory->create($ruleData);
$checkRuleProcessor = $ruleParser->parse($ruleDefinition);

echo "rule:\n" . json_encode($ruleDefinition, JSON_PRETTY_PRINT) . "\n";

echo "\nProcessing event with property = 1:\n";
$checkRuleProcessor->process(createEventExchange("test", 1));

echo "\nProcessing event with property = 2:\n";
$checkRuleProcessor->process(createEventExchange("test", 2));