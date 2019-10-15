<?php


namespace SAREhub\EasyECA\DI\Rule\Processor\EventRules;


use SAREhub\Client\DI\Processor\ProcessorDefinitionHelper;
use SAREhub\Client\Event\EventHelper;
use SAREhub\Client\Message\Exchange;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;
use SAREhub\EasyECA\Util\MulticastGroupsRouter;
use function DI\autowire;
use function DI\create;
use function DI\get;

class EventRulesProcessorDefinitions
{
    public const PROCESSOR = "EasyECA.Rule.Processor.EventRules";

    public static function get(): array
    {
        return [
            static::PROCESSOR => create(MulticastGroupsRouter::class)->constructor(static::eventTypeRoutingFunction()),
            EventRuleGroupManager::class => autowire()->constructorParameter("router", get(static::PROCESSOR)),
        ];
    }

    private static function eventTypeRoutingFunction(): callable
    {
        return ProcessorDefinitionHelper::closureValue(function (Exchange $exchange) {
            return EventHelper::extract($exchange->getIn())->getType();
        });
    }
}
