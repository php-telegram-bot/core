<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class BusinessBotRights
 *
 * Represents the rights of a business bot.
 *
 * @link https://core.telegram.org/bots/api#businessbotrights
 *
 * @method bool getCanReply()                    Optional. True, if the bot can send and edit messages in the private chats that had incoming messages in the last 24 hours
 * @method bool getCanReadMessages()             Optional. True, if the bot can mark incoming private messages as read
 * @method bool getCanDeleteSentMessages()       Optional. True, if the bot can delete messages sent by the bot
 * @method bool getCanDeleteAllMessages()        Optional. True, if the bot can delete all private messages in managed chats
 * @method bool getCanEditName()                 Optional. True, if the bot can edit the first and last name of the business account
 * @method bool getCanEditBio()                  Optional. True, if the bot can edit the bio of the business account
 * @method bool getCanEditProfilePhoto()         Optional. True, if the bot can edit the profile photo of the business account
 * @method bool getCanEditUsername()             Optional. True, if the bot can edit the username of the business account
 * @method bool getCanChangeGiftSettings()       Optional. True, if the bot can change the privacy settings pertaining to gifts for the business account
 * @method bool getCanViewGiftsAndStars()        Optional. True, if the bot can view gifts and the amount of Telegram Stars owned by the business account
 * @method bool getCanConvertGiftsToStars()      Optional. True, if the bot can convert regular gifts owned by the business account to Telegram Stars
 * @method bool getCanTransferAndUpgradeGifts()  Optional. True, if the bot can transfer and upgrade gifts owned by the business account
 * @method bool getCanTransferStars()            Optional. True, if the bot can transfer Telegram Stars received by the business account to its own account, or use them to upgrade and transfer gifts
 * @method bool getCanManageStories()            Optional. True, if the bot can post, edit and delete stories on behalf of the business account
 * @method bool getCanManageChecklists()         Optional. True, if the bot can send and edit checklists on behalf of the business account
 *
 * @method $this setCanReply(bool $can_reply)
 * @method $this setCanReadMessages(bool $can_read_messages)
 * @method $this setCanDeleteSentMessages(bool $can_delete_sent_messages)
 * @method $this setCanDeleteAllMessages(bool $can_delete_all_messages)
 * @method $this setCanEditName(bool $can_edit_name)
 * @method $this setCanEditBio(bool $can_edit_bio)
 * @method $this setCanEditProfilePhoto(bool $can_edit_profile_photo)
 * @method $this setCanEditUsername(bool $can_edit_username)
 * @method $this setCanChangeGiftSettings(bool $can_change_gift_settings)
 * @method $this setCanViewGiftsAndStars(bool $can_view_gifts_and_stars)
 * @method $this setCanConvertGiftsToStars(bool $can_convert_gifts_to_stars)
 * @method $this setCanTransferAndUpgradeGifts(bool $can_transfer_and_upgrade_gifts)
 * @method $this setCanTransferStars(bool $can_transfer_stars)
 * @method $this setCanManageStories(bool $can_manage_stories)
 * @method $this setCanManageChecklists(bool $can_manage_checklists)
 */
class BusinessBotRights extends Entity
{
}
