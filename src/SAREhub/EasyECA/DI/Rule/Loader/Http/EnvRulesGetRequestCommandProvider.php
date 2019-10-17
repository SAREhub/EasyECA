<?php


namespace SAREhub\EasyECA\DI\Rule\Loader\Http;

use GuzzleHttp\Client;
use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\EasyECA\Util\HttpGetRequestCommand;

class EnvRulesGetRequestCommandProvider extends InvokableProvider
{
    const DEFAULT_REQUEST_URI_ENVVAR = "RULES_GET_REQUEST_URI";

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $requestUriEnvVar;

    public function __construct(Client $client, string $requestUriEnvVar = self::DEFAULT_REQUEST_URI_ENVVAR)
    {
        $this->client = $client;
        $this->requestUriEnvVar = $requestUriEnvVar;
    }

    public function get()
    {
        $uri = EnvironmentHelper::getRequiredVar($this->requestUriEnvVar);
        return new HttpGetRequestCommand($this->client, $uri);
    }
}
