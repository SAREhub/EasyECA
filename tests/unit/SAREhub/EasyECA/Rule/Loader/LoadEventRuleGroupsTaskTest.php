<?php

namespace SAREhub\EasyECA\Rule\Loader;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;

class LoadEventRuleGroupsTaskTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testRun()
    {
        $loader = \Mockery::mock(EventRuleGroupsLoader::class);
        $groupManager = \Mockery::mock(EventRuleGroupManager::class);
        $task = new LoadEventRuleGroupsTask($loader, $groupManager);

        $group = \Mockery::mock(EventRuleGroupDefinition::class);
        $loader->expects("load")->andReturn([$group]);
        $groupManager->expects("add")->with($group);

        $task->run();
    }
}
