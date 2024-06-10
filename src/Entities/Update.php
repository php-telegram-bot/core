<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int                          getUpdateId()                The update's unique identifier. Update identifiers start from a certain positive number and increase sequentially. This identifier becomes especially handy if you're using webhooks, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
 * @method Message|null                 getMessage()                 Optional. New incoming message of any kind - text, photo, sticker, etc.
 * @method Message|null                 getEditedMessage()           Optional. New version of a message that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method Message|null                 getChannelPost()             Optional. New incoming channel post of any kind - text, photo, sticker, etc.
 * @method Message|null                 getEditedChannelPost()       Optional. New version of a channel post that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method BusinessConnection|null      getBusinessConnection()      Optional. The bot was connected to or disconnected from a business account, or a user edited an existing connection with the bot
 * @method Message|null                 getBusinessMessage()         Optional. New message from a connected business account
 * @method Message|null                 getEditedBusinessMessage()   Optional. New version of a message from a connected business account
 * @method BusinessMessagesDeleted|null getDeletedBusinessMessages() Optional. Messages were deleted from a connected business account
 * @method MessageReactionUpdated       getMessageReaction()         Optional. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must explicitly specify "message_reaction" in the list of allowed_updates to receive these updates. The update isn't received for reactions set by bots.
 * @method MessageReactionCountUpdated  getMessageReactionCount()    Optional. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in the chat and must explicitly specify "message_reaction_count" in the list of allowed_updates to receive these updates. The updates are grouped and can be sent with delay up to a few minutes.
 * @method InlineQuery|null             getInlineQuery()             Optional. New incoming inline query
 * @method ChosenInlineResult|null      getChosenInlineResult()      Optional. The result of an inline query that was chosen by a user and sent to their chat partner. Please see our documentation on the feedback collecting for details on how to enable these updates for your bot.
 * @method CallbackQuery|null           getCallbackQuery()           Optional. New incoming callback query
 * @method ShippingQuery|null           getShippingQuery()           Optional. New incoming shipping query. Only for invoices with flexible price
 * @method PreCheckoutQuery|null        getPreCheckoutQuery()        Optional. New incoming pre-checkout query. Contains full information about checkout
 * @method Poll|null                    getPoll()                    Optional. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by the bot
 * @method PollAnswer|null              getPollAnswer()              Optional. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
 * @method ChatMemberUpdated|null       getMyChatMember()            Optional. The bot's chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
 * @method ChatMemberUpdated|null       getChatMember()              Optional. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify "chat_member" in the list of allowed_updates to receive these updates.
 * @method ChatJoinRequest|null         getChatJoinRequest()         Optional. A request to join the chat has been sent. The bot must have the can_invite_users administrator right in the chat to receive these updates.
 * @method ChatBoostUpdated|null        getChatBoost()               Optional. A chat boost was added or changed. The bot must be an administrator in the chat to receive these updates.
 * @method ChatBoostRemoved|null        getRemovedChatBoost()        Optional. A boost was removed from a chat. The bot must be an administrator in the chat to receive these updates.
 */
class Update extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'message'                   => Message::class,
            'edited_message'            => Message::class,
            'channel_post'              => Message::class,
            'edited_channel_post'       => Message::class,
            'business_connection'       => BusinessConnection::class,
            'business_message'          => Message::class,
            'edited_business_message'   => Message::class,
            'deleted_business_messages' => BusinessMessagesDeleted::class,
            'message_reaction'          => MessageReactionUpdated::class,
            'message_reaction_count'    => MessageReactionCountUpdated::class,
            'inline_query'              => InlineQuery::class,
            'chosen_inline_result'      => ChosenInlineResult::class,
            'callback_query'            => CallbackQuery::class,
            'shipping_query'            => ShippingQuery::class,
            'pre_checkout_query'        => PreCheckoutQuery::class,
            'poll'                      => Poll::class,
            'poll_answer'               => PollAnswer::class,
            'my_chat_member'            => ChatMemberUpdated::class,
            'chat_member'               => ChatMemberUpdated::class,
            'chat_join_request'         => ChatJoinRequest::class,
            'chat_boost'                => ChatBoostUpdated::class,
            'removed_chat_boost'        => ChatBoostRemoved::class,
        ];
    }
}
