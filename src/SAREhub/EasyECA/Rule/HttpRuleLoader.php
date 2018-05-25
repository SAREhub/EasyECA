<?php

namespace SAREhub\EasyECA\Rule;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SAREhub\EasyECA\Rule\Loader\EventRulesLoader;
use SAREhub\EasyECA\Rule\Loader\LoadStrategy\EventLoadStrategy;

class HttpRuleLoader implements EventRulesLoader
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $url;

    /**
     * @var EventLoadStrategy
     */
    private $strategy;

    public function __construct(Client $client, string $url, EventLoadStrategy $strategy)
    {
        $this->client = $client;
        $this->url = $url;
        $this->strategy = $strategy;
    }

    public function load(): array
    {
        $decodedResponseBody = $this->getDecodedResponseBody($this->client->get($this->url));
        return $this->strategy->load($decodedResponseBody);
    }

    private function getDecodedResponseBody(ResponseInterface $response): array
    {
        return json_decode($response->getBody(), true);
    }
}