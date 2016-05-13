<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class Message extends Entity
{
    protected $message_id;

    protected $from;

    protected $date;

    protected $chat;

    protected $forward_from;

    protected $forward_from_chat;

    protected $forward_date;

    protected $reply_to_message;

    protected $text;

    protected $audio;

    protected $document;

    protected $photo;

    protected $sticker;

    protected $video;

    protected $voice;

    protected $caption;

    protected $contact;

    protected $location;

    protected $venue;

    protected $new_chat_member;

    protected $left_chat_member;

    protected $new_chat_title;

    protected $new_chat_photo;

    protected $delete_chat_photo;

    protected $group_chat_created;

    protected $supergroup_chat_created;

    protected $channel_chat_created;

    protected $migrate_to_chat_id;

    protected $migrate_from_chat_id;

    protected $pinned_message;

    protected $entities;

    private $command;

    private $type;

    public function __construct(array $data, $bot_name)
    {

        $this->reply_to_message = isset($data['reply_to_message']) ? $data['reply_to_message'] : null;
        if (!empty($this->reply_to_message)) {
            $this->reply_to_message = new ReplyToMessage($this->reply_to_message, $bot_name);
        }

        $this->init($data, $bot_name);
    }

    //Common init to Message and ReplyToMessage
    protected function init(array & $data, & $bot_name)
    {
        $this->bot_name = $bot_name;

        $this->type = 'Message';

        $this->message_id = isset($data['message_id']) ? $data['message_id'] : null;
        if (empty($this->message_id)) {
            throw new TelegramException('message_id is empty!');
        }

        $this->from = isset($data['from']) ? $data['from'] : null;
        if (!empty($this->from)) {
            $this->from = new User($this->from);
        }

        $this->chat = isset($data['chat']) ? $data['chat'] : null;
        if (empty($this->chat)) {
            throw new TelegramException('chat is empty!');
        }
        $this->chat = new Chat($this->chat);

        $this->date = isset($data['date']) ? $data['date'] : null;
        if (empty($this->date)) {
            throw new TelegramException('date is empty!');
        }

        $this->forward_from = isset($data['forward_from']) ? $data['forward_from'] : null;
        if (!empty($this->forward_from)) {
            $this->forward_from = new User($this->forward_from);

        }

        $this->forward_from_chat = isset($data['forward_from_chat']) ? $data['forward_from_chat'] : null;
        if (!empty($this->forward_from_chat)) {
            $this->forward_from_chat = new Chat($this->forward_from_chat);
        }

        $this->forward_date = isset($data['forward_date']) ? $data['forward_date'] : null;

        $this->text = isset($data['text']) ? $data['text'] : null;
        $command = $this->getCommand();
        if (!empty($command)) {
            $this->type = 'command';
        }

        $this->audio = isset($data['audio']) ? $data['audio'] : null;
        if (!empty($this->audio)) {
            $this->audio = new Audio($this->audio);
            $this->type = 'Audio';
        }

        $this->document = isset($data['document']) ? $data['document'] : null;
        if (!empty($this->document)) {
            $this->document = new Document($this->document);
            $this->type = 'Document';
        }

        $this->photo = isset($data['photo']) ? $data['photo'] : null; //array of photosize
        if (!empty($this->photo)) {
            foreach ($this->photo as $photo) {
                if (!empty($photo)) {
                    $photos[] = new PhotoSize($photo);
                }
            }
            $this->photo = $photos;
            $this->type = 'Photo';
        }

        $this->sticker = isset($data['sticker']) ? $data['sticker'] : null;
        if (!empty($this->sticker)) {
            $this->sticker = new Sticker($this->sticker);
            $this->type = 'Sticker';
        }

        $this->video = isset($data['video']) ? $data['video'] : null;
        if (!empty($this->video)) {
            $this->video = new Video($this->video);
            $this->type = 'Video';
        }

        $this->voice = isset($data['voice']) ? $data['voice'] : null;
        if (!empty($this->voice)) {
            $this->voice = new Voice($this->voice);
            $this->type = 'Voice';
        }

        $this->caption = isset($data['caption']) ? $data['caption'] : null;//string

        $this->contact = isset($data['contact']) ? $data['contact'] : null;
        if (!empty($this->contact)) {
            $this->contact = new Contact($this->contact);
        }

        $this->location = isset($data['location']) ? $data['location'] : null;
        if (!empty($this->location)) {
            $this->location = new Location($this->location);
            $this->type = 'Location';
        }

        $this->venue = isset($data['venue']) ? $data['venue'] : null;
        if (!empty($this->venue)) {
            $this->venue = new Venue($this->venue);
            $this->type = 'Venue';
        }

        //retrocompatibility
        if (isset($data['new_chat_participant'])) {
            $data['new_chat_member'] = $data['new_chat_participant'];
        }

        if (isset($data['left_chat_participant'])) {
            $data['left_chat_member'] = $data['left_chat_participant'];
        }

        $this->new_chat_member = isset($data['new_chat_member']) ? $data['new_chat_member'] : null;
        if (!empty($this->new_chat_member)) {
            $this->new_chat_member = new User($this->new_chat_member);
            $this->type = 'new_chat_member';
        }

        $this->left_chat_member = isset($data['left_chat_member']) ? $data['left_chat_member'] : null;
        if (!empty($this->left_chat_member)) {
            $this->left_chat_member = new User($this->left_chat_member);
            $this->type = 'left_chat_member';
        }

        $this->new_chat_title = isset($data['new_chat_title']) ? $data['new_chat_title'] : null;
        if (!is_null($this->new_chat_title)) {
            $this->type = 'new_chat_title';
        }

        $this->new_chat_photo = isset($data['new_chat_photo']) ? $data['new_chat_photo'] : null; //array of photosize
        if (!empty($this->new_chat_photo)) {
            foreach ($this->new_chat_photo as $photo) {
                if (!empty($photo)) {
                    $photos[] = new PhotoSize($photo);
                }
            }
            $this->new_chat_photo = $photos;
            $this->type = 'new_chat_photo';
        }

        $this->delete_chat_photo = isset($data['delete_chat_photo']) ? $data['delete_chat_photo'] : null;
        if ($this->delete_chat_photo) {
            $this->type = 'delete_chat_photo';
        }

        $this->group_chat_created = isset($data['group_chat_created']) ? $data['group_chat_created'] : null;
        if ($this->group_chat_created) {
            $this->type = 'group_chat_created';
        }

        $this->supergroup_chat_created = isset($data['supergroup_chat_created']) ? $data['supergroup_chat_created'] : null;
        if ($this->supergroup_chat_created) {
            $this->type = 'supergroup_chat_created';
        }

        $this->channel_chat_created = isset($data['channel_chat_created']) ? $data['channel_chat_created'] : null;
        if ($this->channel_chat_created) {
            $this->type = 'channel_chat_created';
        }

        $this->migrate_to_chat_id = isset($data['migrate_to_chat_id']) ? $data['migrate_to_chat_id'] : null;
        if ($this->migrate_to_chat_id) {
            $this->type = 'migrate_to_chat_id';
        }

        $this->migrate_from_chat_id = isset($data['migrate_from_chat_id']) ? $data['migrate_from_chat_id'] : null;
        if ($this->migrate_from_chat_id) {
            $this->type = 'migrate_from_chat_id';
        }

        $this->pinned_message = isset($data['pinned_message']) ? $data['pinned_message'] : null;
        if ($this->pinned_message) {
            $this->pinned_message = new Message($this->pinned_message, $this->getBotName());
        }

        $this->entities = isset($data['entities']) ? $data['entities'] : null;
        if (!empty($this->entities)) {
            foreach ($this->entities as $entity) {
                if (!empty($entity)) {
                    $entities[] = new MessageEntity($entity);
                }
            }
            $this->entities = $entities;
        }
    }

    //return the entire command like /echo or /echo@bot1 if specified
    public function getFullCommand()
    {
        if (substr($this->text, 0, 1) === '/') {
            $no_EOL = strtok($this->text, PHP_EOL);
            $no_space = strtok($this->text, ' ');

            //try to understand which separator \n or space divide /command from text
            if (strlen($no_space) < strlen($no_EOL)) {
                return $no_space;
            } else {
                return $no_EOL;
            }
        } else {
            return;
        }
    }

    public function getCommand()
    {
        if (!empty($this->command)) {
            return $this->command;
        }

        $cmd = $this->getFullCommand();

        if (substr($cmd, 0, 1) === '/') {
            $cmd = substr($cmd, 1);

            //check if command is follow by botname
            $split_cmd = explode('@', $cmd);
            if (isset($split_cmd[1])) {
                //command is followed by name check if is addressed to me
                if (strtolower($split_cmd[1]) == strtolower($this->bot_name)) {
                    return $this->command = $split_cmd[0];
                }
            } else {
                //command is not followed by name
                return $this->command = $cmd;
            }
        }

        return false;
    }

    public function getMessageId()
    {
        return $this->message_id;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getChat()
    {
        return $this->chat;
    }

    public function getForwardFrom()
    {
        return $this->forward_from;
    }

    public function getForwardFromChat()
    {
        return $this->forward_from_chat;
    }

    public function getForwardDate()
    {
        return $this->forward_date;
    }

    public function getReplyToMessage()
    {
        return $this->reply_to_message;
    }

    public function getText($without_cmd = false)
    {
        $text = $this->text;
        if ($without_cmd) {
            $command = $this->getFullCommand();
            if (!empty($command)) {
                //$text = substr($text, strlen($command.' '), strlen($text));
                $text = substr($text, strlen($command) + 1, strlen($text));
            }
        }

        return $text;
    }

    public function getAudio()
    {
        return $this->audio;
    }
    public function getDocument()
    {
        return $this->document;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getSticker()
    {
        return $this->sticker;
    }

    public function getVideo()
    {
        return $this->video;
    }

    public function getVoice()
    {
        return $this->voice;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getVenue()
    {
        return $this->venue;
    }

    public function getNewChatParticipant()
    {
        return $this->new_chat_member;
    }

    public function getLeftChatParticipant()
    {
        return $this->left_chat_member;
    }

    public function getNewChatMember()
    {
        return $this->new_chat_member;
    }

    public function getLeftChatMember()
    {
        return $this->left_chat_member;
    }

    public function getNewChatTitle()
    {
        return $this->new_chat_title;
    }

    public function getNewChatPhoto()
    {
        return $this->new_chat_photo;
    }

    public function getDeleteChatPhoto()
    {
        return $this->delete_chat_photo;
    }

    public function getGroupChatCreated()
    {
        return $this->group_chat_created;
    }

    public function getSupergroupChatCreated()
    {
        return $this->supergroup_chat_created;
    }

    public function getChannelChatCreated()
    {
        return $this->channel_chat_created;
    }

    public function getMigrateToChatId()
    {
        return $this->migrate_to_chat_id;
    }

    public function getMigrateFromChatId()
    {
        return $this->migrate_from_chat_id;
    }

    public function botAddedInChat()
    {
        if (!empty($this->new_chat_member)) {
            if ($this->new_chat_member->getUsername() == $this->getBotName()) {
                return true;
            }
        }

        return false;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getPinnedMessage()
    {
        return $this->pinned_message;
    }

    public function getEntities()
    {
        return $this->entities;
    }
}
