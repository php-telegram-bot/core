<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Entities\Entity;
use PhpTelegramBot\Core\Entities\Factory;

/**
 * @method string getStatus() The member's status in the chat
 */
class ChatMember extends Entity implements Factory
{
    public const TYPE_CREATOR = 'creator';

    public const TYPE_ADMINISTRATOR = 'administrator';

    public const TYPE_MEMBER = 'member';

    public const TYPE_RESTRICTED = 'restricted';

    public const TYPE_LEFT = 'left';

    public const TYPE_KICKED = 'kicked';

    public static function make(array $data): static
    {
        return match ($data['type']) {
            self::TYPE_CREATOR => new ChatMemberOwner($data),
            self::TYPE_ADMINISTRATOR => new ChatMemberAdministrator($data),
            self::TYPE_MEMBER => new ChatMemberMember($data),
            self::TYPE_RESTRICTED => new ChatMemberRestricted($data),
            self::TYPE_LEFT => new ChatMemberLeft($data),
            self::TYPE_KICKED => new ChatMemberBanned($data),
        };
    }
}
