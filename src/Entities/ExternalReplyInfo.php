<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * This object contains information about a message that is being replied to, which may come from another chat or forum topic.
 *
 * @link https://core.telegram.org/bots/api#externalreplyinfo
 *
 * @method MessageOrigin      getOrigin()             Origin of the message replied to by the given message
 * @method Chat               getChat()               Optional. Chat the original message belongs to. Available only if the chat is a supergroup or a channel.
 * @method int                getMessageId()          Optional. Unique message identifier inside the original chat. Available only if the original chat is a supergroup or a channel.
 * @method LinkPreviewOptions getLinkPreviewOptions() Optional. Options used for link preview generation for the original message, if it is a text message
 * @method Animation          getAnimation()          Optional. Message is an animation, information about the animation
 * @method Audio              getAudio()              Optional. Message is an audio file, information about the file
 * @method Document           getDocument()           Optional. Message is a general file, information about the file
 * @method PhotoSize[]        getPhoto()              Optional. Message is a photo, available sizes of the photo
 * @method Sticker            getSticker()            Optional. Message is a sticker, information about the sticker
 * @method Story              getStory()              Optional. Message is a forwarded story
 * @method Video              getVideo()              Optional. Message is a video, information about the video
 * @method VideoNote          getVideoNote()          Optional. Message is a video note, information about the video message
 * @method Voice              getVoice()              Optional. Message is a voice message, information about the file
 * @method bool               getHasMediaSpoiler()    Optional. True, if the message media is covered by a spoiler animation
 * @method Contact            getContact()            Optional. Message is a shared contact, information about the contact
 * @method Dice               getDice()               Optional. Message is a dice with random value
 * @method Game               getGame()               Optional. Message is a game, information about the game. More about games »
 * @method Giveaway           getGiveaway()           Optional. Message is a scheduled giveaway, information about the giveaway
 * @method GiveawayWinners    getGiveawayWinners()    Optional. A giveaway with public winners was completed
 * @method Invoice            getInvoice()            Optional. Message is an invoice for a payment, information about the invoice. More about payments »
 * @method Location           getLocation()           Optional. Message is a shared location, information about the location
 * @method Poll               getPoll()               Optional. Message is a native poll, information about the poll
 * @method Venue              getVenue()              Optional. Message is a venue, information about the venue
 */
class ExternalReplyInfo extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'origin'               => MessageOrigin::class,
            'chat'                 => Chat::class,
            'link_preview_options' => LinkPreviewOptions::class,
            'animation'            => Animation::class,
            'audio'                => Audio::class,
            'document'             => Document::class,
            'photo'                => [PhotoSize::class],
            'sticker'              => Sticker::class,
            'story'                => Story::class,
            'video'                => Video::class,
            'video_note'           => VideoNote::class,
            'voice'                => Voice::class,
            'contact'              => Contact::class,
            'dice'                 => Dice::class,
            'game'                 => Game::class,
            'giveaway'             => Giveaway::class,
            'giveaway_winners'     => GiveawayWinners::class,
            'invoice'              => Invoice::class,
            'location'             => Location::class,
            'poll'                 => Poll::class,
            'venue'                => Venue::class,
        ];
    }
}
