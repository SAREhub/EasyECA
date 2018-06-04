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

        $this->router->expects("removeFromAll")->withArgs(["test_group_id"]);

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
