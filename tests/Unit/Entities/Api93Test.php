<?php

namespace Longman\TelegramBot\Tests\Unit\Entities;

use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\UniqueGiftInfo;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\ChatFullInfo;
use Longman\TelegramBot\Entities\BusinessConnection;
use Longman\TelegramBot\Entities\AffiliateInfo;
use Longman\TelegramBot\Entities\StarTransaction;
use Longman\TelegramBot\Tests\Unit\TestCase;

class Api93Test extends TestCase
{
    public function testUserNewFields(): void
    {
        $user = new User([
            'id' => 123,
            'first_name' => 'Test',
            'has_topics_enabled' => true,
            'allows_users_to_create_topics' => true,
        ]);
        self::assertTrue($user->getHasTopicsEnabled());
        self::assertTrue($user->getAllowsUsersToCreateTopics());
    }

    public function testMessageNewFields(): void
    {
        $message = new Message([
            'message_id' => 1,
            'date' => 123456789,
            'chat' => ['id' => 123, 'type' => 'private'],
            'sender_tag' => 'vip',
            'show_caption_above_media' => true,
            'is_paid_post' => true,
        ]);
        self::assertEquals('vip', $message->getSenderTag());
        self::assertTrue($message->getShowCaptionAboveMedia());
        self::assertTrue($message->getIsPaidPost());
    }

    public function testUniqueGiftInfoReplacedFields(): void
    {
        $info = new UniqueGiftInfo([
            'gift' => [
                'id' => '123',
                'sticker' => ['file_id' => 'abc', 'file_unique_id' => 'def', 'type' => 'regular', 'width' => 512, 'height' => 512],
                'star_count' => 10,
            ],
            'origin' => 'resale',
            'last_resale_currency' => 'XTR',
            'last_resale_amount' => 100,
        ]);
        self::assertEquals('XTR', $info->getLastResaleCurrency());
        self::assertEquals(100, $info->getLastResaleAmount());
        self::assertNull($info->getProperty('last_resale_star_count'));
    }

    public function testNewUpdateTypes(): void
    {
        $update = new Update([
            'update_id' => 1,
            'purchased_paid_media' => [
                'from' => ['id' => 123, 'first_name' => 'John'],
                'paid_media_payload' => 'abc',
            ],
        ]);
        self::assertEquals('purchased_paid_media', $update->getUpdateType());
        self::assertInstanceOf(\Longman\TelegramBot\Entities\PaidMediaPurchased::class, $update->getPurchasedPaidMedia());
        self::assertEquals('abc', $update->getPurchasedPaidMedia()->getPaidMediaPayload());
    }

    public function testChatFullInfoNewFields(): void
    {
        $chat = new ChatFullInfo([
            'id' => 123,
            'type' => 'private',
            'first_name' => 'John',
            'rating' => ['level' => 5, 'rating' => 1000, 'current_level_rating' => 900],
            'paid_message_star_count' => 10,
        ]);
        self::assertInstanceOf(\Longman\TelegramBot\Entities\UserRating::class, $chat->getRating());
        self::assertEquals(5, $chat->getRating()->getLevel());
        self::assertEquals(10, $chat->getPaidMessageStarCount());
    }

    public function testBusinessConnectionUpdate(): void
    {
        $conn = new BusinessConnection([
            'id' => 'conn_id',
            'user' => ['id' => 123, 'first_name' => 'Biz'],
            'user_chat_id' => 123,
            'date' => 123456789,
            'is_enabled' => true,
            'rights' => ['can_reply' => true, 'can_manage_checklists' => true],
        ]);
        self::assertEquals('conn_id', $conn->getId());
        self::assertTrue($conn->getRights()->getCanManageChecklists());
    }

    public function testStarTransactionWithTransactionPartner(): void
    {
        $trans = new StarTransaction([
            'id' => 'trans_id',
            'amount' => 100,
            'date' => 123456789,
            'source' => [
                'type' => 'affiliate_program',
                'sponsor_user' => ['id' => 1, 'first_name' => 'Sponsor'],
                'commission_per_mille' => 100,
            ],
            'receiver' => [
                'type' => 'user',
                'user' => ['id' => 2, 'first_name' => 'Receiver'],
            ],
        ]);
        self::assertInstanceOf(\Longman\TelegramBot\Entities\TransactionPartnerAffiliateProgram::class, $trans->getSource());
        self::assertEquals(100, $trans->getSource()->getCommissionPerMille());
        self::assertInstanceOf(\Longman\TelegramBot\Entities\TransactionPartnerUser::class, $trans->getReceiver());
    }
}
