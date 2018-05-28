<?php

namespace SAREhub\EasyECA\Rule;


use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;

class ConfigureRuleGroupsProcessor implements Processor
{
    const RULE_GROUP_CHANGED_ROUTE = "rule_group_changed";
    const RULE_GROUP_DELETED_ROUTE = "rule_group_deleted";

    /**
     * @var Processor
     */
    private $ruleGroupChangedProcessor;

    /**
     * @var Processor
     */
    private $ruleGroupDeletedProcessor;

    public function __construct(Processor $ruleGroupChangedProcessor, Processor $ruleGroupDeletedProcessor)
    {
        $this->ruleGroupChangedProcessor = $ruleGroupChangedProcessor;
        $this->ruleGroupDeletedProcessor = $ruleGroupDeletedProcessor;
    }

    public function process(Exchange $exchange)
    {
        Processors::router(function (Exchange $exchange) {
            return $exchange->getIn()->getBody()["type"];
        })->addRoute(self::RULE_GROUP_CHANGED_ROUTE,
            $this->ruleGroupChangedProcessor)->addRoute(self::RULE_GROUP_DELETED_ROUTE,
                $this->ruleGroupDeletedProcessor)->process($exchange);
    }
}