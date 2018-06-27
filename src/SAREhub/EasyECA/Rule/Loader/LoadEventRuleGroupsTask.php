<?php

namespace SAREhub\EasyECA\Rule\Loader;

use SAREhub\Commons\Task\Task;
use SAREhub\EasyECA\Rule\EventRuleGroupManager;

/**
 * Task to simplify loading event rule groups from loader and injecting to manager
 */
class LoadEventRuleGroupsTask implements Task
{
    /**
     * @var EventRuleGroupsLoader
     */
    private $loader;

    /**
     * @var EventRuleGroupManager
     */
    private $groupManager;

    public function __construct(EventRuleGroupsLoader $loader, EventRuleGroupManager $groupManager)
    {
        $this->loader = $loader;
        $this->groupManager = $groupManager;
    }

    public function run()
    {
        $groups = $this->loader->load();
        foreach ($groups as $group) {
            $this->groupManager->add($group);
        }
    }
}