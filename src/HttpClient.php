<?php

namespace PhpTelegramBot\Core;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class HttpClient
{
    public function __construct(
        protected ?ClientInterface $client = null,
        protected ?RequestFactoryInterface $requestFactory = null,
        protected ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->client ??= Psr18ClientDiscovery::find();
        $this->requestFactory ??= Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory ??= Psr17FactoryDiscovery::findStreamFactory();
    }

    public function get(string $uri): ResponseInterface
    {
        return $this->client->sendRequest(
            $this->requestFactory->createRequest('GET', $uri)
        );
    }

    public function postJson(string $uri, array $data): ResponseInterface
    {
        $json = json_encode($data);

        return $this->client->sendRequest(
            $this->requestFactory->createRequest('POST', $uri)
                ->withHeader('Content-Type', 'application/json')
                ->withBody(
                    $this->streamFactory->createStream($json)
                )
        );
    }
}
