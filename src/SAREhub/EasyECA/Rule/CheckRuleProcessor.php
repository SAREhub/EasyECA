<?php

namespace SAREhub\EasyECA\Rule;

use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;

class CheckRuleProcessor implements Processor
{

    /**
     * @var RuleAsserterService
     */
    private $asserterService;

    /**
     * @var mixed
     */
    private $condition;

    /**
     * @var Processor
     */
    private $onPass;

    /**
     * @var Processor
     */
    private $onFail;

    public function __construct(RuleAsserterService $asserterService, $condition, Processor $onPass, Processor $onFail)
    {
        $this->asserterService = $asserterService;
        $this->condition = $condition;
        $this->onPass = $onPass;
        $this->onFail = $onFail;
    }

    public function process(Exchange $exchange)
    {
        if ($this->asserterService->assert($this->getCondition(), $exchange)) {
            $this->getOnPass()->process($exchange);
        } else {
            $this->getOnFail()->process($exchange);
        }
    }

    /**
     * @return RuleAsserterService
     */
    public function getAsserterService(): RuleAsserterService
    {
        return $this->asserterService;
    }

    /**
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return Processor
     */
    public function getOnPass(): Processor
    {
        return $this->onPass;
    }

    /**
     * @return Processor
     */
    public function getOnFail(): Processor
    {
        return $this->onFail;
    }
}
