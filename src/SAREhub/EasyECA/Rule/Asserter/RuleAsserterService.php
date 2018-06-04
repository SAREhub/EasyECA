<?php

namespace SAREhub\EasyECA\Rule\Asserter;

class RuleAsserterService
{
    /**
     * @var RuleAssertContextFactory
     */
    private $assertContextFactory;

    /**
     * @var RuleAsserter
     */
    private $asserter;

    public function __construct(RuleAssertContextFactory $assertContextFactory, RuleAsserter $asserter)
    {
        $this->assertContextFactory = $assertContextFactory;
        $this->asserter = $asserter;
    }


    /**
     * @param mixed $condition
     * @param mixed $data
     * @return bool
     * @throws RuleAssertException
     */
    public function assert($condition, $data): bool
    {
        return $this->asserter->assert($condition, $this->createAssertContext($data));
    }

    /**
     * @param mixed $data
     * @return array
     */
    private function createAssertContext($data): array
    {
        return $this->assertContextFactory->create($data);
    }
}

