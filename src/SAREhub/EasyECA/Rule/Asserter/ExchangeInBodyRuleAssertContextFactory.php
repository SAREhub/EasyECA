<?php


namespace SAREhub\EasyECA\Rule\Asserter;


use SAREhub\Client\Message\Exchange;

class ExchangeInBodyRuleAssertContextFactory implements RuleAssertContextFactory
{
    /**
     * @var string
     */
    private $inMessageBodyContextKey;

    /**
     * @param string $inMessageBodyContextKey
     */
    public function __construct(string $inMessageBodyContextKey)
    {
        $this->inMessageBodyContextKey = $inMessageBodyContextKey;
    }

    public function create($data): array
    {
        return $this->createFromExchange($data);
    }

    private function createFromExchange(Exchange $exchange)
    {
        return [
            $this->inMessageBodyContextKey => $exchange->getIn()->getBody()
        ];
    }
}