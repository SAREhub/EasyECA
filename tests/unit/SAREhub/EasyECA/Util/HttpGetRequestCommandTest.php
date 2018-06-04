<?php

namespace SAREhub\EasyECA\Util;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class HttpGetRequestCommandTest extends TestCase
{

    public function testExecute()
    {
        $expectedResponse = new Response(200);
        $mockHandler = new MockHandler([$expectedResponse]);

        $stack = HandlerStack::create($mockHandler);
        $container = [];
        $stack->push(Middleware::history($container));

        $client = new Client(["handler" => $stack]);

        $uri = "http://test.test";

        $command = new HttpGetRequestCommand($client, $uri);
        $response = $command->execute();
        /** @var Request $request */
        $request = $container[0]["request"];
        $this->assertEquals("GET", $request->getMethod(), "request method");
        $this->assertEquals($uri, $request->getUri(), "request uri");

        $this->assertSame($expectedResponse, $response, "returned response");
    }
}
