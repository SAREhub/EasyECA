<?php

namespace SAREhub\EasyECA\Util;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class HttpGetRequestCommand
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $uri;

    public function __construct(Client $client, string $uri)
    {
        $this->client = $client;
        $this->uri = $uri;
    }

    public function execute(): ResponseInterface
    {
        return $this->client->get($this->uri);
    }
}
