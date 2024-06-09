<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\MessageOrigin\MessageOrigin;

/**
 * @method MessageOrigin           getOrigin()             Origin of the message replied to by the given message
 * @method Chat|null               getChat()               Optional. Chat the original message belongs to. Available only if the chat is a supergroup or a channel.
 * @method int|null                getMessageId()          Optional. Unique message identifier inside the original chat. Available only if the original chat is a supergroup or a channel.
 * @method LinkPreviewOptions|null getLinkPreviewOptions() Optional. Options used for link preview generation for the original message, if it is a text message
 * @method Animation|null          getAnimation()          Optional. Message is an animation, information about the animation
 * @method Audio|null              getAudio()              Optional. Message is an audio file, information about the file
 * @method Document|null           getDocument()           Optional. Message is a general file, information about the file
 * @method PhotoSize[]|null        getPhoto()              Optional. Message is a photo, available sizes of the photo
 * @method Sticker|null            getSticker()            Optional. Message is a sticker, information about the sticker
 * @method Story|null              getStory()              Optional. Message is a forwarded story
 * @method Video|null              getVideo()              Optional. Message is a video, information about the video
 * @method VideoNote|null          getVideoNote()          Optional. Message is a video note, information about the video message
 * @method Voice|null              getVoice()              Optional. Message is a voice message, information about the file
 * @method true|null               getHasMediaSpoiler()    Optional. True, if the message media is covered by a spoiler animation
 * @method Contact|null            getContact()            Optional. Message is a shared contact, information about the contact
 * @method Dice|null               getDice()               Optional. Message is a dice with random value
 * @method Game|null               getGame()               Optional. Message is a game, information about the game. More about games »
 * @method Giveaway|null           getGiveaway()           Optional. Message is a scheduled giveaway, information about the giveaway
 * @method GiveawayWinners|null    getGiveawayWinners()    Optional. A giveaway with public winners was completed
 * @method Invoice|null            getInvoice()            Optional. Message is an invoice for a payment, information about the invoice. More about payments »
 * @method Location|null           getLocation()           Optional. Message is a shared location, information about the location
 * @method Poll|null               getPoll()               Optional. Message is a native poll, information about the poll
 * @method Venue|null              getVenue()              Optional. Message is a venue, information about the venue
 */
class ExternalReplyInfo extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'origin' => MessageOrigin::class,
            'chat' => Chat::class,
            'link_preview_options' => LinkPreviewOptions::class,
            'animation' => Animation::class,
            'audio' => Audio::class,
            'document' => Document::class,
            'photo' => [PhotoSize::class],
            'sticker' => Sticker::class,
            'story' => Story::class,
            'video' => Video::class,
            'video_note' => VideoNote::class,
            'voice' => Voice::class,
            'contact' => Contact::class,
            'dice' => Dice::class,
            'game' => Game::class,
            'giveaway' => Giveaway::class,
            'giveaway_winners' => GiveawayWinners::class,
            'invoice' => Invoice::class,
            'location' => Location::class,
            'poll' => Poll::class,
            'venue' => Venue::class,
        ];
    }
}
