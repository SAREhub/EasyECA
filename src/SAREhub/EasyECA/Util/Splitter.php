<?php


namespace SAREhub\EasyECA\Util;


use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\Processor;

class Splitter implements Processor
{
    /**
     * @var Processor
     */
    private $to;

    /**
     * @var SplittingStrategy
     */
    private $strategy;

    public function __construct(Processor $to, SplittingStrategy $strategy)
    {
        $this->to = $to;
        $this->strategy = $strategy;
    }

    public function process(Exchange $exchange)
    {
        foreach ($this->strategy->split($exchange->getIn()) as $exchange) {
            $this->to->process($exchange);
        }
    }
}