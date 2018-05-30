<?php

namespace SAREhub\EasyECA\Util;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Client\Message\BasicExchange;
use SAREhub\Client\Message\BasicMessage;
use SAREhub\Client\Processor\Processor;

class SplitterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | SplittingStrategy
     */
    private $strategy;

    /**
     * @var MockInterface | Processor
     */
    private $to;

    /**
     * @var Splitter
     */
    private $splitter;

    public function setUp()
    {
        $this->to = \Mockery::mock(Processor::class);
        $this->strategy = \Mockery::mock(SplittingStrategy::class);
        $this->splitter = new Splitter($this->to, $this->strategy);
    }

    public function testProcess()
    {
        $inBody = ["partOne", "partTwo"];

        $exchange = BasicExchange::withIn(BasicMessage::newInstance()->setBody($inBody));

        $splittedExchange = BasicExchange::newInstance();

        $this->strategy->expects("split")->withArgs([$exchange->getIn()])->andReturn([
            $splittedExchange,
        ]);

        $this->to->expects("process")->withArgs([$splittedExchange]);

        $this->splitter->process($exchange);
    }

    /**
     * @return MockInterface | Processor
     */
    protected function createProcessor(): Processor
    {
        return $partProcessor = \Mockery::mock(Processor::class);
    }

}
