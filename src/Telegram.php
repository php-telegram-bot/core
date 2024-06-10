<?php

namespace PhpTelegramBot\Core;

use PhpTelegramBot\Core\Exceptions\NotYetImplementedException;
use PhpTelegramBot\Core\Exceptions\TelegramException;

class Telegram
{
    protected string $apiBaseUri = 'https://api.telegram.org';

    public function __construct(
        #[\SensitiveParameter]
        protected string $botToken,
        protected ?string $botUsername = null,
        protected ?HttpClient $client = null,
    ) {
        $this->client = new HttpClient();
    }

    public function __call(string $methodName, array $arguments)
    {
        $requestUri = $this->apiBaseUri.'/bot'.$this->botToken.'/'.$methodName;

        $data = $arguments[0] ?? null;

        $response = match (true) {
            $data === null => $this->client->get($requestUri),
            default        => $this->client->postJson($requestUri, $data),
        };

        $result = json_decode($response->getBody()->getContents(), true);
        if ($result['ok'] !== true) {
            throw new TelegramException(
                $result['description'],
                $result['error_code'] ?? 0,
            );
        }

        return $result['result'];
    }

    public function handleGetUpdates()
    {
        throw new NotYetImplementedException();
    }

    public function handle()
    {
        throw new NotYetImplementedException();
    }

    protected function processUpdate()
    {
        throw new NotYetImplementedException();
    }
}
