<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Entities\User;

/**
 * @method User getUser()                  Information about the user
 * @method bool getIsMember()              True, if the user is a member of the chat at the moment of the request
 * @method bool getCanSendMessages()       True, if the user is allowed to send text messages, contacts, giveaways, giveaway winners, invoices, locations and venues
 * @method bool getCanSendAudios()         True, if the user is allowed to send audios
 * @method bool getCanSendDocuments()      True, if the user is allowed to send documents
 * @method bool getCanSendPhotos()         True, if the user is allowed to send photos
 * @method bool getCanSendVideos()         True, if the user is allowed to send videos
 * @method bool getCanSendVideoNotes()     True, if the user is allowed to send video notes
 * @method bool getCanSendVoiceNotes()     True, if the user is allowed to send voice notes
 * @method bool getCanSendPolls()          True, if the user is allowed to send polls
 * @method bool getCanSendOtherMessages()  True, if the user is allowed to send animations, games, stickers and use inline bots
 * @method bool getCanAddWebPagePreviews() True, if the user is allowed to add web page previews to their messages
 * @method bool getCanChangeInfo()         True, if the user is allowed to change the chat title, photo and other settings
 * @method bool getCanInviteUsers()        True, if the user is allowed to invite new users to the chat
 * @method bool getCanPinMessages()        True, if the user is allowed to pin messages
 * @method bool getCanManageTopics()       True, if the user is allowed to create forum topics
 * @method int  getUntilDate()             Date when restrictions will be lifted for this user; Unix time. If 0, then the user is restricted forever
 */
class ChatMemberRestricted extends ChatMember
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
