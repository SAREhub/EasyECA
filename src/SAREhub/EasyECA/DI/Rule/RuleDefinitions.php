<?php


namespace SAREhub\EasyECA\DI\Rule;

use SAREhub\Client\DI\Worker\WorkerDefinitions;
use SAREhub\EasyECA\DI\Hoa\Rule\Asserter\HoaRuleAsserterProvider;
use SAREhub\EasyECA\DI\Rule\Loader\Http\EnvRulesGetRequestCommandProvider;
use SAREhub\EasyECA\DI\Rule\Loader\Http\RulesHttpResponseParsingStrategy;
use SAREhub\EasyECA\Hoa\Rule\Asserter\UtilRuleOperators;
use SAREhub\EasyECA\Rule\Asserter\ExchangeInBodyRuleAssertContextFactory;
use SAREhub\EasyECA\Rule\Asserter\RuleAssertContextFactory;
use SAREhub\EasyECA\Rule\Asserter\RuleAsserter;
use SAREhub\EasyECA\Rule\Loader\EventRuleGroupsLoader;
use SAREhub\EasyECA\Rule\Loader\Http\HttpEventRuleGroupsLoader;
use SAREhub\EasyECA\Rule\Loader\Http\HttpResponseParsingStrategy;
use SAREhub\EasyECA\Rule\Loader\LoadEventRuleGroupsTask;
use SAREhub\EasyECA\Util\HttpGetRequestCommand;
use function DI\add;
use function DI\create;
use function DI\factory;
use function DI\get;

class RuleDefinitions
{
    public static function get(): array
    {
        return [
            RuleAssertContextFactory::class => self::createEventRuleAssertContextFactory(),
            HoaRuleAsserterProvider::class => create()->constructor([get(UtilRuleOperators::class)]),
            RuleAsserter::class => factory(HoaRuleAsserterProvider::class),
            HttpGetRequestCommand::class => factory(EnvRulesGetRequestCommandProvider::class),
            HttpResponseParsingStrategy::class => get(RulesHttpResponseParsingStrategy::class),
            EventRuleGroupsLoader::class => get(HttpEventRuleGroupsLoader::class),
            WorkerDefinitions::ENTRY_INIT_TASKS => add([
                get(LoadEventRuleGroupsTask::class)
            ])
        ];
    }

    private static function createEventRuleAssertContextFactory()
    {
        return create(ExchangeInBodyRuleAssertContextFactory::class)->constructor("event");
    }
}
