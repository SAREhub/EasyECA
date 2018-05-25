<?php

namespace SAREhub\EasyECA\Rule;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class HttpRuleLoader implements RuleLoader
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $key;

    /**
     * @var Client
     */
    private $client;

    public static function newInstance(Client $client): self
    {
        return new self($client);
    }

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function load(): array
    {
        $decodedResponseBody = $this->getDecodedResponseBody($this->client->get($this->getFrom()));
        return $decodedResponseBody[$this->getKey()];
    }

    private function getDecodedResponseBody(ResponseInterface $response): array
    {
        return json_decode($response->getBody(), true);
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): HttpRuleLoader
    {
        $this->from = $from;
        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): HttpRuleLoader
    {
        $this->key = $key;
        return $this;
    }
}