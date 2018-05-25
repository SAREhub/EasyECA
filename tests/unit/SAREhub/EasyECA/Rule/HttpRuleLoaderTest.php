<?php

namespace SAREhub\EasyECA\Rule;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class HttpRuleLoaderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testLoadThenReturnRulesInArray()
    {
        $responseBody = '{"a":{"rules":[{"type":"event"}]}}';

        /** @var Client | MockInterface $client */
        $client = \Mockery::mock(Client::class);

        $loader = HttpRuleLoader::newInstance($client)->setKey("a")->setFrom("from");
        $client->expects("get")->withArgs(["from"])->andReturn(new Response(200, [], $responseBody));

        $this->assertEquals(["rules" => [
            ["type" => "event"]
        ]], $loader->load());
    }
}
