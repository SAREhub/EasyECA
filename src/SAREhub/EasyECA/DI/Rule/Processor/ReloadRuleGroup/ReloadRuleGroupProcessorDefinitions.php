<?php


namespace SAREhub\EasyECA\DI\Rule\Processor\ReloadRuleGroup;

use SAREhub\Client\DI\Processor\ProcessorDefinitionHelper;
use SAREhub\Client\Processor\TransformProcessor;
use SAREhub\EasyECA\Event\AddEventRuleGroupProcessor;
use SAREhub\EasyECA\Event\AddEventRuleGroupsProcessorProvider;
use SAREhub\EasyECA\Event\DefaultEventTypeRuleGroupsSplittingStrategy;
use SAREhub\EasyECA\Event\ReconfigureRuleGroupProcessorProvider;
use SAREhub\EasyECA\Event\RemoveRuleGroupProcessor;
use function DI\create;
use function DI\factory;
use function DI\get;

abstract class ReloadRuleGroupProcessorDefinitions
{
    public const PROCESSOR = "EasyECA.Rule.Processor.ReloadRuleGroup";

    protected const ACTION_REPLACE = "replace";
    protected const ACTION_REMOVE = "remove";

    public static function get()
    {
        return [
            AddEventRuleGroupsProcessorProvider::class => static::addEventRuleGroupsProcessorProvider(),
            ReconfigureRuleGroupProcessorProvider::class => static::reconfigureRuleGroupProcessorProvider(),
            static::PROCESSOR => static::processor()
        ];
    }

    protected static function processor()
    {
        return ProcessorDefinitionHelper::pipeline([
            static::actionTransformToRuleGroupEventRouter(),
            factory(ReconfigureRuleGroupProcessorProvider::class)
        ]);
    }

    protected static function actionTransformToRuleGroupEventRouter()
    {
        $routingFunction = static::actionRoutingFunction();
        $routes = static::actionTransformRoutes();
        return ProcessorDefinitionHelper::router($routingFunction, $routes);
    }

    protected static abstract function actionRoutingFunction();

    protected static function actionTransformRoutes(): array
    {
        return [
            static::ACTION_REPLACE => static::transformToReplaceEvent(),
            static::ACTION_REMOVE => static::transformToRemoveEvent()
        ];
    }

    protected static function transformToReplaceEvent()
    {
        $ruleGroupIdExtractor = static::wrapCallableFunction(static::ruleGroupIdExtractor());
        $rulesExtractor = static::wrapCallableFunction(static::ruleGroupRulesExtractor());
        $transformer = create(RuleGroupChangedEventTransformer::class)
            ->constructor($ruleGroupIdExtractor, $rulesExtractor);
        return create(TransformProcessor::class)->constructor($transformer);
    }

    protected static function transformToRemoveEvent()
    {
        $ruleGroupIdExtractor = static::wrapCallableFunction(static::ruleGroupIdExtractor());
        $transformer = create(RuleGroupRemovedEventTransformer::class)->constructor($ruleGroupIdExtractor);
        return create(TransformProcessor::class)->constructor($transformer);
    }

    /**
     * @return mixed|callable Function to extract rule group id from in message body
     */
    protected static abstract function ruleGroupIdExtractor();

    /**
     * @return mixed|callable Function to extract rule group rules from in message body
     */
    protected static abstract function ruleGroupRulesExtractor();

    protected static function wrapCallableFunction($function)
    {
        return $function instanceOf \Closure ? ProcessorDefinitionHelper::closureValue($function) : $function;
    }

    protected static function reconfigureRuleGroupProcessorProvider()
    {
        return create(ReconfigureRuleGroupProcessorProvider::class)->constructor(
            get(RemoveRuleGroupProcessor::class),
            factory(AddEventRuleGroupsProcessorProvider::class)
        );
    }

    protected static function addEventRuleGroupsProcessorProvider()
    {
        return create(AddEventRuleGroupsProcessorProvider::class)->constructor(
            get(DefaultEventTypeRuleGroupsSplittingStrategy::class),
            get(AddEventRuleGroupProcessor::class)
        );
    }
}
