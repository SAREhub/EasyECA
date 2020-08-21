<?php

namespace SAREhub\EasyECA\Rule;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Processor\MulticastProcessor;
use SAREhub\EasyECA\Rule\Definition\EventRuleGroupDefinition;
use SAREhub\EasyECA\Rule\Definition\RuleGroupDefinition;
use SAREhub\EasyECA\Util\MulticastGroupsRouter;

class EventRuleGroupManagerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | MulticastGroupsRouter
     */
    private $router;

    /**
     * @var MockInterface | RuleGroupParser
     */
    private $groupParser;

    /**
     * @var EventRuleGroupManager
     */
    private $manager;

    public function setUp(): void
    {
        $this->router = \Mockery::mock(MulticastGroupsRouter::class);
        $this->groupParser = \Mockery::mock(RuleGroupParser::class);
        $this->manager = new EventRuleGroupManager($this->router, $this->groupParser);
    }

    public function testAdd(): void
    {
        $ruleGroup = $this->createRuleGroup("test_group_id");

        $definition = new EventRuleGroupDefinition("test_event_type", $ruleGroup);

        $checkRuleGroupProcessor = $this->createProcessor();
        $this->groupParser->expects("parse")->withArgs([$ruleGroup])->andReturn($checkRuleGroupProcessor);
        $this->router->expects("add")->withArgs(["test_event_type", "test_group_id", $checkRuleGroupProcessor]);

        $this->manager->add($definition);
    }

    public function testRemoveFromAll(): void
    {
        $ruleGroupId = "test_group_id";

        $this->router->expects("removeFromAll")->with("test_group_id");

        $this->manager->removeGroupFromAllEvents($ruleGroupId);
    }

    public function testAddGroupWhenHasListener()
    {
        $ruleGroup = $this->createRuleGroup("test_group_id");
        $definition = new EventRuleGroupDefinition("test_event_type", $ruleGroup);
        $this->groupParser->allows("parse")->andReturn($this->createProcessor());
        $this->router->allows("add");
        $listener = \Mockery::mock(AddRuleGroupListener::class);
        $this->manager->setAddRuleGroupListener($listener);

        $listener->expects("onAddRuleGroup")->with($definition);

        $this->manager->add($definition);

    }

    public function testRemoveFromAllWhenHasListener(): void
    {
        $ruleGroupId = "test_group_id";
        $this->router->allows("removeFromAll");
        $listener = \Mockery::mock(RemoveGroupFromAllEventsListener::class);
        $this->manager->setRemoveGroupFromAllEvents($listener);

        $listener->expects("onRemoveGroupFromAllEvents")->with($ruleGroupId);

        $this->manager->removeGroupFromAllEvents($ruleGroupId);
    }

    protected function createRuleGroup(string $id): RuleGroupDefinition
    {
        return new RuleGroupDefinition($id, []);
    }

    /**
     * @return MockInterface | MulticastProcessor
     */
    protected function createProcessor(): MulticastProcessor
    {
        return \Mockery::mock(MulticastProcessor::class);
    }
}
