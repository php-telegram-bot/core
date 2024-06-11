<?php

namespace PhpTelegramBot\Core;

use PhpTelegramBot\Core\Entities\Factory;
use PhpTelegramBot\Core\Exceptions\NotYetImplementedException;
use PhpTelegramBot\Core\Exceptions\TelegramException;
use PhpTelegramBot\Core\Methods\AnswersInlineQueries;
use PhpTelegramBot\Core\Methods\SendsInvoices;
use PhpTelegramBot\Core\Methods\SendsMessages;
use PhpTelegramBot\Core\Methods\SendsStickers;
use PhpTelegramBot\Core\Methods\UpdatesMessages;

class Telegram
{
    use AnswersInlineQueries;
    use SendsInvoices;
    use SendsMessages;
    use SendsStickers;
    use UpdatesMessages;

    protected string $apiBaseUri = 'https://api.telegram.org';

    public function __construct(
        #[\SensitiveParameter]
        protected string $botToken,
        protected ?string $botUsername = null,
        protected ?HttpClient $client = null,
    ) {
        $this->client = new HttpClient();
    }

    public function __call(string $methodName, array $arguments): mixed
    {
        return $this->send($methodName, $arguments[0] ?? null, $arguments[1] ?? null);
    }

    protected function send(string $methodName, ?array $data = null, string|array|null $returnType = null): mixed
    {
        $requestUri = $this->apiBaseUri.'/bot'.$this->botToken.'/'.$methodName;

        $response = match (true) {
            empty($data) => $this->client->get($requestUri),
            default      => $this->client->postJson($requestUri, $data),
        };

        $result = json_decode($response->getBody()->getContents(), true);
        if ($result['ok'] !== true) {
            throw new TelegramException(
                $result['description'],
                $result['error_code'] ?? 0,
            );
        }

        if ($returnType === null) {
            return $result['result'];
        }

        if (is_array($returnType)) {
            $returnType = $returnType[0];

            return array_map(fn ($item) => $this->makeResultObject($item, $returnType), $result['result']);
        }

        return $this->makeResultObject($result['result'], $returnType);
    }

    protected function makeResultObject(mixed $result, string|array|null $returnType = null): mixed
    {
        if (! is_array($result)) {
            return $result;
        }

        if (is_subclass_of($returnType, Factory::class)) {
            return $returnType::make($result);
        }

        return new $returnType($result);
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
