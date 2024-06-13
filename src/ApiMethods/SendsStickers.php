<?php

namespace PhpTelegramBot\Core\ApiMethods;

use PhpTelegramBot\Core\Entities\File;
use PhpTelegramBot\Core\Entities\ForceReply;
use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputFile;
use PhpTelegramBot\Core\Entities\InputSticker;
use PhpTelegramBot\Core\Entities\MaskPosition;
use PhpTelegramBot\Core\Entities\Message;
use PhpTelegramBot\Core\Entities\ReplyKeyboardMarkup;
use PhpTelegramBot\Core\Entities\ReplyKeyboardRemove;
use PhpTelegramBot\Core\Entities\ReplyParameters;
use PhpTelegramBot\Core\Entities\Sticker;
use PhpTelegramBot\Core\Entities\StickerSet;

trait SendsStickers
{
    /**
     * @param array{
     *     business_connection_id: string,
     *     chat_id: int|string,
     *     message_thread_id: int,
     *     sticker: InputFile|string,
     *     emoji: string,
     *     disable_notification: bool,
     *     protect_content: bool,
     *     message_effect_id: string,
     *     reply_parameters: ReplyParameters,
     *     reply_markup: InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function sendSticker(array $data = []): Message
    {
        return $this->send(__FUNCTION__, $data, Message::class);
    }

    /**
     * @param array{
     *     name: string,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function getStickerSet(array $data = []): StickerSet
    {
        return $this->send(__FUNCTION__, $data, StickerSet::class);
    }

    /**
     * @param array{
     *     custom_emoji_ids: string[],
     * } $data
     * @return Sticker[]
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function getCustomEmojiStickers(array $data = []): array
    {
        return $this->send(__FUNCTION__, $data, [Sticker::class]);
    }

    /**
     * @param array{
     *     user_id: int,
     *     sticker: InputFile,
     *     sticker_format: string,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function uploadStickerFile(array $data = []): File
    {
        return $this->send(__FUNCTION__, $data, File::class);
    }

    /**
     * @param array{
     *     user_id: int,
     *     name: string,
     *     title: string,
     *     stickers: InputSticker[],
     *     sticker_type: string,
     *     needs_repainting: bool,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function createNewStickerSet(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     user_id: int,
     *     name: string,
     *     sticker: InputSticker,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function addStickerToSet(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     sticker: string,
     *     position: int,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function setStickerPositionInSet(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     sticker: string,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function deleteStickerFromSet(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     user_id: int,
     *     name: string,
     *     old_sticker: string,
     *     sticker: InputSticker,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function replaceStickerInSet(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     sticker: string,
     *     emoji_list: string[],
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function setStickerEmojiList(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     sticker: string,
     *     keywords: string[],
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function setStickerKeywords(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     sticker: string,
     *     mask_position: MaskPosition,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function setStickerMaskPosition(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     name: string,
     *     title: string,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function setStickerSetTitle(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     name: string,
     *     user_id: int,
     *     thumbnail: InputFile|string,
     *     format: string,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function setStickerSetThumbnail(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     name: string,
     *     custom_emoji_id: string,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function setCustomEmojiStickerSetThumbnail(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     name: string,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function deleteStickerSet(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }
}
