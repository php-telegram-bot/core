<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\MessageOrigin\MessageOrigin;

/**
 * @method int                                getMessageId()                     Unique message identifier inside this chat
 * @method int|null                           getMessageThreadId()               Optional. Unique identifier of a message thread to which the message belongs; for supergroups only
 * @method User|null                          getFrom()                          Optional. Sender of the message; empty for messages sent to channels. For backward compatibility, the field contains a fake sender user in non-channel chats, if the message was sent on behalf of a chat.
 * @method Chat|null                          getSenderChat()                    Optional. Sender of the message, sent on behalf of a chat. For example, the channel itself for channel posts, the supergroup itself for messages from anonymous group administrators, the linked channel for messages automatically forwarded to the discussion group. For backward compatibility, the field from contains a fake sender user in non-channel chats, if the message was sent on behalf of a chat.
 * @method int|null                           getSenderBoostCount()              Optional. If the sender of the message boosted the chat, the number of boosts added by the user
 * @method User|null                          getSenderBusinessBot()             Optional. The bot that actually sent the message on behalf of the business account. Available only for outgoing messages sent on behalf of the connected business account.
 * @method int                                getDate()                          Date the message was sent in Unix time. It is always a positive number, representing a valid date.
 * @method string|null                        getBusinessConnectionId()          Optional. Unique identifier of the business connection from which the message was received. If non-empty, the message belongs to a chat of the corresponding business account that is independent from any potential bot chat which might share the same identifier.
 * @method Chat                               getChat()                          Chat the message belongs to
 * @method MessageOrigin|null                 getForwardOrigin()                 Optional. Information about the original message for forwarded messages
 * @method true|null                          getIsTopicMessage()                Optional. True, if the message is sent to a forum topic
 * @method true|null                          getIsAutomaticForward()            Optional. True, if the message is a channel post that was automatically forwarded to the connected discussion group
 * @method Message|null                       getReplyToMessage()                Optional. For replies in the same chat and message thread, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @method ExternalReplyInfo|null             getExternalReply()                 Optional. Information about the message that is being replied to, which may come from another chat or forum topic
 * @method TextQuote|null                     getQuote()                         Optional. For replies that quote part of the original message, the quoted part of the message
 * @method Story|null                         getReplyToStory()                  Optional. For replies to a story, the original story
 * @method User|null                          getViaBot()                        Optional. Bot through which the message was sent
 * @method int|null                           getEditDate()                      Optional. Date the message was last edited in Unix time
 * @method true|null                          getHasProtectedContent()           Optional. True, if the message can't be forwarded
 * @method true|null                          getIsFromOffline()                 Optional. True, if the message was sent by an implicit action, for example, as an away or a greeting business message, or as a scheduled message
 * @method string|null                        getMediaGroupId()                  Optional. The unique identifier of a media message group this message belongs to
 * @method string|null                        getAuthorSignature()               Optional. Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
 * @method string|null                        getText()                          Optional. For text messages, the actual UTF-8 text of the message
 * @method MessageEntity[]|null               getEntities()                      Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @method LinkPreviewOptions|null            getLinkPreviewOptions()            Optional. Options used for link preview generation for the message, if it is a text message and link preview options were changed
 * @method string|null                        getEffectId()                      Optional. Unique identifier of the message effect added to the message
 * @method Animation|null                     getAnimation()                     Optional. Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set
 * @method Audio|null                         getAudio()                         Optional. Message is an audio file, information about the file
 * @method Document|null                      getDocument()                      Optional. Message is a general file, information about the file
 * @method PhotoSize[]|null                   getPhoto()                         Optional. Message is a photo, available sizes of the photo
 * @method Sticker|null                       getSticker()                       Optional. Message is a sticker, information about the sticker
 * @method Story|null                         getStory()                         Optional. Message is a forwarded story
 * @method Video|null                         getVideo()                         Optional. Message is a video, information about the video
 * @method VideoNote|null                     getVideoNote()                     Optional. Message is a video note, information about the video message
 * @method Voice|null                         getVoice()                         Optional. Message is a voice message, information about the file
 * @method string|null                        getCaption()                       Optional. Caption for the animation, audio, document, photo, video or voice
 * @method MessageEntity[]|null               getCaptionEntities()               Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
 * @method true|null                          getShowCaptionAboveMedia()         Optional. True, if the caption must be shown above the message media
 * @method true|null                          getHasMediaSpoiler()               Optional. True, if the message media is covered by a spoiler animation
 * @method Contact|null                       getContact()                       Optional. Message is a shared contact, information about the contact
 * @method Dice|null                          getDice()                          Optional. Message is a dice with random value
 * @method Game|null                          getGame()                          Optional. Message is a game, information about the game.
 * @method Poll|null                          getPoll()                          Optional. Message is a native poll, information about the poll
 * @method Venue|null                         getVenue()                         Optional. Message is a venue, information about the venue. For backward compatibility, when this field is set, the location field will also be set
 * @method Location|null                      getLocation()                      Optional. Message is a shared location, information about the location
 * @method User[]|null                        getNewChatMembers()                Optional. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members).
 * @method User|null                          getLeftChatMember()                Optional. A member was removed from the group, information about them (this member may be the bot itself).
 * @method string|null                        getNewChatTitle()                  Optional. A chat title was changed to this value
 * @method PhotoSize[]|null                   getNewChatPhoto()                  Optional. A chat photo was change to this value
 * @method true|null                          getDeleteChatPhoto()               Optional. Service message: the chat photo was deleted
 * @method true|null                          getGroupChatCreated()              Optional. Service message: the group has been created
 * @method true|null                          getSupergroupChatCreated()         Optional. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can't be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @method true|null                          getChannelChatCreated()            Optional. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can't be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
 * @method MessageAutoDeleteTimerChanged|null getMessageAutoDeleteTimerChanged() Optional. Service message: auto-delete timer settings changed in the chat
 * @method int|null                           getMigrateToChatId()               Optional. The group has been migrated to a supergroup with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method int|null                           getMigrateFromChatId()             Optional. The supergroup has been migrated from a group with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method MaybeInaccessibleMessage|null      getPinnedMessage()                 Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @method Invoice|null                       getInvoice()                       Optional. Message is an invoice for a payment, information about the invoice.
 * @method SuccessfulPayment|null             getSuccessfulPayment()             Optional. Message is a service message about a successful payment, information about the payment.
 * @method UsersShared|null                   getUsersShared()                   Optional. Service message: users were shared with the bot
 * @method ChatShared|null                    getChatShared()                    Optional. Service message: a chat was shared with the bot
 * @method string|null                        getConnectedWebsite()              Optional. The domain name of the website on which the user has logged in.
 * @method WriteAccessAllowed|null            getWriteAccessAllowed()            Optional. Service message: the user allowed the bot to write messages after adding it to the attachment or side menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method requestWriteAccess
 * @method PassportData|null                  getPassportData()                  Optional. Telegram Passport data
 * @method ProximityAlertTriggered|null       getProximityAlertTriggered()       Optional. Service message. A user in the chat triggered another user's proximity alert while sharing Live Location.
 * @method ChatBoostAdded|null                getBoostAdded()                    Optional. Service message: user boosted the chat
 * @method ChatBackground|null                getChatBackgroundSet()             Optional. Service message: chat background set
 * @method ForumTopicCreated|null             getForumTopicCreated()             Optional. Service message: forum topic created
 * @method ForumTopicEdited|null              getForumTopicEdited()              Optional. Service message: forum topic edited
 * @method ForumTopicClosed|null              getForumTopicClosed()              Optional. Service message: forum topic closed
 * @method ForumTopicReopened|null            getForumTopicReopened()            Optional. Service message: forum topic reopened
 * @method GeneralForumTopicHidden|null       getGeneralForumTopicHidden()       Optional. Service message: the 'General' forum topic hidden
 * @method GeneralForumTopicUnhidden|null     getGeneralForumTopicUnhidden()     Optional. Service message: the 'General' forum topic unhidden
 * @method GiveawayCreated|null               getGiveawayCreated()               Optional. Service message: a scheduled giveaway was created
 * @method Giveaway|null                      getGiveaway()                      Optional. The message is a scheduled giveaway message
 * @method GiveawayWinners|null               getGiveawayWinners()               Optional. A giveaway with public winners was completed
 * @method GiveawayCompleted|null             getGiveawayCompleted()             Optional. Service message: a giveaway without public winners was completed
 * @method VideoChatScheduled|null            getVideoChatScheduled()            Optional. Service message: video chat scheduled
 * @method VideoChatStarted|null              getVideoChatStarted()              Optional. Service message: video chat started
 * @method VideoChatEnded|null                getVideoChatEnded()                Optional. Service message: video chat ended
 * @method VideoChatParticipantsInvited|null  getVideoChatParticipantsInvited()  Optional. Service message: new participants invited to a video chat
 * @method WebAppData|null                    getWebAppData()                    Optional. Service message: data sent by a Web App
 * @method InlineKeyboardMarkup               getReplyMarkup()                   Optional. Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons.
 */
class Message extends MaybeInaccessibleMessage
{
    protected static function subEntities(): array
    {
        return [
            'from' => User::class,
            'sender_chat' => Chat::class,
            'sender_business_bot' => User::class,
            'chat' => Chat::class,
            'forward_origin' => MessageOrigin::class,
            'reply_to_message' => Message::class,
            'external_reply' => ExternalReplyInfo::class,
            'quote' => TextQuote::class,
            'reply_to_story' => Story::class,
            'via_bot' => User::class,
            'entities' => [MessageEntity::class],
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
            'caption_entities' => [MessageEntity::class],
            'contact' => Contact::class,
            'dice' => Dice::class,
            'game' => Game::class,
            'poll' => Poll::class,
            'venue' => Venue::class,
            'location' => Location::class,
            'new_chat_members' => [User::class],
            'left_chat_member' => User::class,
            'new_chat_photo' => [PhotoSize::class],
            'message_auto_delete_timer_changed' => MessageAutoDeleteTimerChanged::class,
            'pinned_message' => MaybeInaccessibleMessage::class,
            'invoice' => Invoice::class,
            'successful_payment' => SuccessfulPayment::class,
            'users_shared' => UsersShared::class,
            'chat_shared' => ChatShared::class,
            'write_access_allowed' => WriteAccessAllowed::class,
            'passport_data' => PassportData::class,
            'proximity_alert_triggered' => ProximityAlertTriggered::class,
            'boost_added' => ChatBoostAdded::class,
            'chat_background_set' => ChatBackground::class,
            'forum_topic_created' => ForumTopicCreated::class,
            'forum_topic_edited' => ForumTopicEdited::class,
            'forum_topic_closed' => ForumTopicClosed::class,
            'forum_topic_reopened' => ForumTopicReopened::class,
            'general_forum_topic_hidden' => GeneralForumTopicHidden::class,
            'general_forum_topic_unhidden' => GeneralForumTopicUnhidden::class,
            'giveaway_created' => GiveawayCreated::class,
            'giveaway' => Giveaway::class,
            'giveaway_winners' => GiveawayWinners::class,
            'giveaway_completed' => GiveawayCompleted::class,
            'video_chat_scheduled' => VideoChatScheduled::class,
            'video_chat_started' => VideoChatStarted::class,
            'video_chat_ended' => VideoChatEnded::class,
            'video_chat_participants_invited' => VideoChatParticipantsInvited::class,
            'web_app_data' => WebAppData::class,
            'reply_markup' => InlineKeyboardMarkup::class,
        ];
    }
}
