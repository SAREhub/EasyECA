<?php

use Hoa\Ruler\Ruler;
use SAREhub\EasyECA\Hoa\Rule\Asserter\HoaRuleAsserter;
use SAREhub\EasyECA\Rule\Action\ActionDefinitionFactory;
use SAREhub\EasyECA\Rule\Action\ActionParser;
use SAREhub\EasyECA\Rule\Asserter\ExchangeInBodyRuleAssertContextFactory;
use SAREhub\EasyECA\Rule\Asserter\RuleAsserterService;
use SAREhub\EasyECA\Rule\Definition\RuleDefinitionFactory;
use SAREhub\EasyECA\Rule\Definition\RuleGroupDefinitionFactory;
use SAREhub\EasyECA\Rule\RuleGroupParser;
use SAREhub\EasyECA\Rule\RuleParser;
use SAREhub\Example\Util\EchoActionProcessorFactory;

require dirname(__DIR__) . "/bootstrap.php";

$actionDefinitionFactory = new ActionDefinitionFactory();
$ruleDefinitionFactory = new RuleDefinitionFactory($actionDefinitionFactory);
$ruleGroupDefinitionFactory = new RuleGroupDefinitionFactory($ruleDefinitionFactory);

$ruleAsserterService = new RuleAsserterService(
    new ExchangeInBodyRuleAssertContextFactory("event"),
    new HoaRuleAsserter(new Ruler())
);
$ruleParser = new RuleParser($ruleAsserterService, new ActionParser([
    "echo" => new EchoActionProcessorFactory()
]));
$ruleGroupParser = new RuleGroupParser($ruleParser);

$ruleGroupData = [
    "id" => "test_group",
    "rules" => [
        [
            "condition" => "event.property = 1",
            "onPass" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnPass action of rule 1!"
                ],
            ],
            "onFail" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnFail action of rule 1!"
                ],
            ]
        ],
        [
            "condition" => "event.property = 2",
            "onPass" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnPass action of rule 2!"
                ],
            ],
            "onFail" => [
                "action" => "echo",
                "parameters" => [
                    "message" => "Im in OnFail action of rule 2!"
                ],
            ]
        ]
    ]
];
$ruleGroupDefinition = $ruleGroupDefinitionFactory->create($ruleGroupData);
$processor = $ruleGroupParser->parse($ruleGroupDefinition);

echo "rule group:\n" . json_encode($ruleGroupDefinition, JSON_PRETTY_PRINT) . "\n";

echo "\nProcessing event with property = 1:\n";
$processor->process(createEventExchange("test", 1));

echo "\nProcessing event with property = 2:\n";
$processor->process(createEventExchange("test", 2));