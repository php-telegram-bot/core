<?php

namespace PhpTelegramBot\Core;

use PhpTelegramBot\Core\ApiMethods\AnswersInlineQueries;
use PhpTelegramBot\Core\ApiMethods\SendsInvoices;
use PhpTelegramBot\Core\ApiMethods\SendsMessages;
use PhpTelegramBot\Core\ApiMethods\SendsStickers;
use PhpTelegramBot\Core\ApiMethods\UpdatesMessages;
use PhpTelegramBot\Core\Contracts\Factory;
use PhpTelegramBot\Core\Entities\Update;
use PhpTelegramBot\Core\Exceptions\TelegramException;
use Psr\Http\Message\StreamInterface;

use function PhpTelegramBot\Core\Helpers\array_is_assoc;

class Telegram
{
    use AnswersInlineQueries;
    use SendsInvoices;
    use SendsMessages;
    use SendsStickers;
    use UpdatesMessages;

    protected string $apiBaseUri = 'https://api.telegram.org';

    public static function inputFileFields(): array
    {
        return [
            'addStickerToSet'        => [
                'sticker' => ['sticker'],
            ],
            'createNewStickerSet'    => [
                'stickers' => ['sticker'],
            ],
            'editMessageMedia'       => [
                'media' => ['media'],
            ],
            'replaceStickerInSet'    => [
                'sticker' => ['sticker'],
            ],
            'sendAnimation'          => ['animation', 'thumbnail'],
            'sendAudio'              => ['audio', 'thumbnail'],
            'sendDocument'           => ['document', 'thumbnail'],
            'sendMediaGroup'         => [
                'media' => ['media', 'thumbnail'],
            ],
            'sendPhoto'              => ['photo'],
            'sendSticker'            => ['sticker'],
            'sendVideo'              => ['video', 'thumbnail'],
            'sendVideoNote'          => ['video_note', 'thumbnail'],
            'sendVoice'              => ['voice'],
            'setChatPhoto'           => ['photo'],
            'setStickerSetThumbnail' => ['thumbnail'],
            'setWebhook'             => ['certificate'],
            'uploadStickerFile'      => ['sticker'],
        ];
    }

    public function __construct(
        #[\SensitiveParameter]
        protected string $botToken,
        protected ?string $botUsername = null,
        protected ?HttpClient $client = null,
    ) {
        $this->client ??= new HttpClient();
    }

    public function __call(string $methodName, array $arguments): mixed
    {
        return $this->send($methodName, $arguments[0] ?? null, $arguments[1] ?? null);
    }

    /**
     * @return StreamInterface[]
     */
    protected function extractFiles(array $fields, array &$data): array
    {
        $streams = [];
        foreach ($fields as $key => $value) {

            if (is_string($value)) {
                if (! isset($data[$value])) {
                    continue;
                }

                $file = $data[$value];

                if (is_string($file) && is_file($file) || is_resource($file) || $file instanceof StreamInterface) {
                    $fileId = uniqid($value . '_');
                    $data[$value] = 'attach://' . $fileId;

                    $streams[$fileId] = match (true) {
                        is_string($file) && is_file($file) => $this->client->streamFactory()->createStreamFromFile($file),
                        is_resource($file)                 => $this->client->streamFactory()->createStreamFromResource($file),
                        $file instanceof StreamInterface   => $file,
                    };
                }
            } elseif (! array_is_assoc($data[$key])) {

                foreach ($data[$key] as &$item) {
                    $streams += $this->extractFiles($value, $item);
                }
            } else {

                $streams += $this->extractFiles($value, $data[$key]);
            }
        }

        return $streams;
    }

    protected function send(string $methodName, ?array $data = null, string|array|null $returnType = null): mixed
    {
        $requestUri = $this->apiBaseUri . '/bot' . $this->botToken . '/' . $methodName;

        $streams = $this->extractFiles(self::inputFileFields()[$methodName] ?? null, $data);

        $response = match (true) {
            empty($data)        => $this->client->get($requestUri),
            count($streams) > 0 => $this->client->postMultipart($requestUri, $data, $streams),
            default             => $this->client->postJson($requestUri, $data),
        };

        $result = json_decode($response->getBody()->getContents(), true);

        if ($result === null || $result['ok'] !== true) {
            throw new TelegramException(
                $result['description'] ?? $response->getReasonPhrase(),
                $result['error_code'] ?? $response->getStatusCode(),
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

    public function handleGetUpdates(int $pollingInterval = 30, ?array $allowedUpdates = null)
    {
        $offset = null;
        while (true) {
            $updates = $this->getUpdates([
                'offset'          => $offset,
                'timeout'         => $pollingInterval,
                'allowed_updates' => $allowedUpdates,
            ]);

            foreach ($updates as $update) {
                $this->processUpdate($update);
                $offset = $update->getUpdateId() + 1;
            }
        }
    }

    public function handle()
    {
        $data = file_get_contents('php://input');
        $json = json_decode($data, true);

        $update = new Update($json);

        $this->processUpdate($update);
    }

    protected function processUpdate(Update $update)
    {
        //
    }
}
