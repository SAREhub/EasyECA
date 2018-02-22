<?php

namespace SAREhub\EasyECA\Rule;

use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\NullProcessor;
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

    public function __construct(RuleAsserterService $asserterService, $condition)
    {
        $this->asserterService = $asserterService;
        $this->condition = $condition;
        $this->onPass = new NullProcessor();
        $this->onFail = new NullProcessor();
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
     * @param Processor $onPass
     */
    public function setOnPass(Processor $onPass)
    {
        $this->onPass = $onPass;
    }

    /**
     * @return Processor
     */
    public function getOnFail(): Processor
    {
        return $this->onFail;
    }

    /**
     * @param Processor $onFail
     */
    public function setOnFail(Processor $onFail): void
    {
        $this->onFail = $onFail;
    }

    public function __toString()
    {
        return 'CheckRule[' . $this->getRule() . ' ? ' . $this->getOnPass() . ' : ' . $this->getOnFail() . ']';
    }

}