<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\User;

/**
 * @method User getUser()               Information about the user
 * @method bool isMember()              True, if the user is a member of the chat at the moment of the request
 * @method bool canSendMessages()       True, if the user is allowed to send text messages, contacts, giveaways, giveaway winners, invoices, locations and venues
 * @method bool canSendAudios()         True, if the user is allowed to send audios
 * @method bool canSendDocuments()      True, if the user is allowed to send documents
 * @method bool canSendPhotos()         True, if the user is allowed to send photos
 * @method bool canSendVideos()         True, if the user is allowed to send videos
 * @method bool canSendVideoNotes()     True, if the user is allowed to send video notes
 * @method bool canSendVoiceNotes()     True, if the user is allowed to send voice notes
 * @method bool canSendPolls()          True, if the user is allowed to send polls
 * @method bool canSendOtherMessages()  True, if the user is allowed to send animations, games, stickers and use inline bots
 * @method bool canAddWebPagePreviews() True, if the user is allowed to add web page previews to their messages
 * @method bool canChangeInfo()         True, if the user is allowed to change the chat title, photo and other settings
 * @method bool canInviteUsers()        True, if the user is allowed to invite new users to the chat
 * @method bool canPinMessages()        True, if the user is allowed to pin messages
 * @method bool canManageTopics()       True, if the user is allowed to create forum topics
 * @method int  getUntilDate()          Date when restrictions will be lifted for this user; Unix time. If 0, then the user is restricted forever
 */
class ChatMemberRestricted extends ChatMember implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_member'                 => false,
            'can_send_messages'         => false,
            'can_send_audios'           => false,
            'can_send_documents'        => false,
            'can_send_photos'           => false,
            'can_send_videos'           => false,
            'can_send_video_notes'      => false,
            'can_send_voice_notes'      => false,
            'can_send_polls'            => false,
            'can_send_other_messages'   => false,
            'can_add_web_page_previews' => false,
        ];
    }
}
