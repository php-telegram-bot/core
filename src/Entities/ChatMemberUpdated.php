<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\ChatMember\ChatMember;

/**
 * @method Chat                getChat()                    Chat the user belongs to
 * @method User                getFrom()                    Performer of the action, which resulted in the change
 * @method int                 getDate()                    Date the change was done in Unix time
 * @method ChatMember          getOldChatMember()           Previous information about the chat member
 * @method ChatMember          getNewChatMember()           New information about the chat member
 * @method ChatInviteLink|null getInviteLink()              Optional. Chat invite link, which was used by the user to join the chat; for joining by invite link events only.
 * @method bool|null           getViaJoinRequest()          Optional. True, if the user joined the chat after sending a direct join request without using an invite link and being approved by an administrator
 * @method bool|null           getViaChatFolderInviteLink() Optional. True, if the user joined the chat via a chat folder invite link
 */
class ChatMemberUpdated extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'from' => User::class,
            'old_chat_member' => ChatMember::class,
            'new_chat_member' => ChatMember::class,
            'invite_link' => ChatInviteLink::class,
        ];
    }
}
