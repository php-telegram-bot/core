<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class SuggestedPostParameters
 *
 * This object describes parameters for a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostparameters
 *
 * @method int    getDirectMessagesTopicId() Optional. Unique identifier of the direct messages chat topic where the post is suggested
 * @method string getAuthorSignature()       Optional. For channel direct messages chats only, the signature of the suggested post author
 * @method bool   getIsPaidPost()            Optional. For channel direct messages chats only, True if the post is a paid post
 */
class SuggestedPostParameters extends Entity
{
}
