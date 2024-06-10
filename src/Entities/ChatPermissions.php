<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method bool canSendMessages()       Optional. True, if the user is allowed to send text messages, contacts, giveaways, giveaway winners, invoices, locations and venues
 * @method bool canSendAudios()         Optional. True, if the user is allowed to send audios
 * @method bool canSendDocuments()      Optional. True, if the user is allowed to send documents
 * @method bool canSendPhotos()         Optional. True, if the user is allowed to send photos
 * @method bool canSendVideos()         Optional. True, if the user is allowed to send videos
 * @method bool canSendVideoNotes()     Optional. True, if the user is allowed to send video notes
 * @method bool canSendVoiceNotes()     Optional. True, if the user is allowed to send voice notes
 * @method bool canSendPolls()          Optional. True, if the user is allowed to send polls
 * @method bool canSendOtherMessages()  Optional. True, if the user is allowed to send animations, games, stickers and use inline bots
 * @method bool canAddWebPagePreviews() Optional. True, if the user is allowed to add web page previews to their messages
 * @method bool canChangeInfo()         Optional. True, if the user is allowed to change the chat title, photo and other settings. Ignored in public supergroups
 * @method bool canInviteUsers()        Optional. True, if the user is allowed to invite new users to the chat
 * @method bool canPinMessages()        Optional. True, if the user is allowed to pin messages. Ignored in public supergroups
 * @method bool canManageTopics()       Optional. True, if the user is allowed to create forum topics. If omitted defaults to the value of can_pin_message
 */
class ChatPermissions extends Entity implements AllowsBypassingGet
{
    //
    public static function fieldsBypassingGet(): array
    {
        return [
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
            'can_change_info'           => false,
        ];
    }
}
