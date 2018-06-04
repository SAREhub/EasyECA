<?php

namespace SAREhub\EasyECA\Util;

use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Processor\MulticastProcessor;
use SAREhub\Client\Processor\Processor;
use SAREhub\Client\Processor\Processors;
use SAREhub\Client\Processor\Router;

class MulticastGroupsRouter implements Processor
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(callable $routingFunction)
    {
        $this->router = Processors::router($routingFunction);
    }

    public function process(Exchange $exchange)
    {
        $this->router->process($exchange);
    }

    public function add(string $routingKey, string $memberId, Processor $processor)
    {
        if (!$this->hasRoute($routingKey)) {
            $this->router->addRoute($routingKey, Processors::multicast());
        }
        $this->getRoute($routingKey)->set($memberId, $processor);
    }

    public function removeFromAll(string $memberId)
    {
        foreach ($this->getRoutes() as $routingKey => $route) {
            $route->remove($memberId);
            if (count($route->getProcessors()) === 0) {
                $this->router->removeRoute($routingKey);
            }
        }
    }

    public function getRoute(string $routingKey): MulticastProcessor
    {
        return $this->router->getRoute($routingKey);
    }

    /**
     * @param string $routingKey
     * @return Processor[]
     */
    public function getRouteMembers(string $routingKey): array
    {
        return $this->getRoute($routingKey)->getProcessors();
    }

    public function hasRoute(string $routingKey): bool
    {
        return $this->router->hasRoute($routingKey);
    }

    /**
     * @return MulticastProcessor[]
     */
    public function getRoutes(): array
    {
        return $this->router->getRoutes();
    }
}
