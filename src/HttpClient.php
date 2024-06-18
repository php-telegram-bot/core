<?php

namespace PhpTelegramBot\Core;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

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

    /**
     * @param array<StreamInterface> $streams
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function postMultipart(string $uri, array $data, array $streams): ResponseInterface
    {
        $builder = new MultipartStreamBuilder();

        // Add data
        foreach ($data as $key => $value) {
            $value = match (true) {
                is_array($value)    => json_encode($value),
                default             => (string) $value,
            };

            $builder->addResource($key, $value);
        }

        // Add file streams
        foreach ($streams as $fileId => $stream) {
            $builder->addResource($fileId, $stream);
        }

        $boundary = $builder->getBoundary();

        return $this->client->sendRequest(
            $this->requestFactory->createRequest('POST', $uri)
                ->withHeader('Content-Type', "multipart/form-data; boundary=\"$boundary\"")
                ->withBody($builder->build())
        );
    }

    public function streamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }
}
