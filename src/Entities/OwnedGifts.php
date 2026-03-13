<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class OwnedGifts
 *
 * Contains the list of gifts received and owned by a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#ownedgifts
 *
 * @method int         getTotalCount() The total number of gifts owned by the user or the chat
 * @method OwnedGift[] getGifts()      The list of gifts
 * @method string      getNextOffset() Optional. Offset for the next request. If empty, then there are no more results
 */
class OwnedGifts extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            // OwnedGift can be Regular or Unique. Using Factory logic here might be needed or just returning Entity is not enough.
            // Since this library uses array of classes for polymorphic types usually in subEntities it doesn't support it directly unless custom logic.
            // But let's check how Update or Message handles multiple types.
            // Usually it maps to a specific class. Here we have OwnedGift which is a union type.
            // We can define a OwnedGift class that factory-creates the right one, or just map 'gifts' to a base class.
            // For now, let's assume we need an OwnedGift factory or similar.
            // Or we can rely on the fact that Entity constructor handles arrays.
            // But we need to know WHICH class to instantiate.
            // Let's create an OwnedGift factory class.
            'gifts' => [OwnedGift::class],
        ];
    }
}
