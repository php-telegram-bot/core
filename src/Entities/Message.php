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
    /**
     * @var mixed|null
     */
    protected $message_id;

    /**
     * @var \Longman\TelegramBot\Entities\User|null
     */
    protected $from;

    /**
     * @var mixed|null
     */
    protected $date;

    /**
     * @var \Longman\TelegramBot\Entities\Chat|null
     */
    protected $chat;

    /**
     * @var \Longman\TelegramBot\Entities\User|null
     */
    protected $forward_from;

    /**
     * @var \Longman\TelegramBot\Entities\Chat|null
     */
    protected $forward_from_chat;

    /**
     * @var mixed|null
     */
    protected $forward_date;

    /**
     * @var mixed|null
     */
    protected $edit_date;

    /**
     * @var \Longman\TelegramBot\Entities\ReplyToMessage
     */
    protected $reply_to_message;

    /**
     * @var string|null
     */
    protected $text;

    /**
     * @var \Longman\TelegramBot\Entities\Audio|null
     */
    protected $audio;

    /**
     * @var \Longman\TelegramBot\Entities\Document|null
     */
    protected $document;

    /**
     * @var array|null
     */
    protected $photo;

    /**
     * @var \Longman\TelegramBot\Entities\Sticker|null
     */
    protected $sticker;

    /**
     * @var \Longman\TelegramBot\Entities\Video|null
     */
    protected $video;

    /**
     * @var \Longman\TelegramBot\Entities\Voice|null
     */
    protected $voice;

    /**
     * @var mixed|null
     */
    protected $caption;

    /**
     * @var \Longman\TelegramBot\Entities\Contact|null
     */
    protected $contact;

    /**
     * @var \Longman\TelegramBot\Entities\Location|null
     */
    protected $location;

    /**
     * @var mixed|null
     */
    protected $venue;

    /**
     * @var \Longman\TelegramBot\Entities\User|null
     */
    protected $new_chat_member;

    /**
     * @var \Longman\TelegramBot\Entities\User|null
     */
    protected $left_chat_member;

    /**
     * @var mixed|null
     */
    protected $new_chat_title;

    /**
     * @var mixed|null
     */
    protected $new_chat_photo;

    /**
     * @var mixed|null
     */
    protected $delete_chat_photo;

    /**
     * @var mixed|null
     */
    protected $group_chat_created;

    /**
     * @var mixed|null
     */
    protected $supergroup_chat_created;

    /**
     * @var mixed|null
     */
    protected $channel_chat_created;

    /**
     * @var mixed|null
     */
    protected $migrate_to_chat_id;

    /**
     * @var mixed|null
     */
    protected $migrate_from_chat_id;

    /**
     * @var mixed|null
     */
    protected $pinned_message;

    /**
     * @var mixed|null
     */
    protected $entities;

    /**
     * @var mixed|null
     */
    private $command;

    /**
     * @var mixed|null
     */
    private $type;

    /**
     * Message constructor.
     *
     * @param array $data
     * @param       $bot_name
     */
    public function __construct(array $data, $bot_name)
    {

        $this->reply_to_message = isset($data['reply_to_message']) ? $data['reply_to_message'] : null;
        if (!empty($this->reply_to_message)) {
            $this->reply_to_message = new ReplyToMessage($this->reply_to_message, $bot_name);
        }

        $this->init($data, $bot_name);
    }

    /**
     * Common init to Message and ReplyToMessage
     *
     * @param array $data
     * @param       $bot_name
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
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

        $this->edit_date = isset($data['edit_date']) ? $data['edit_date'] : null;

        $this->text = isset($data['text']) ? $data['text'] : null;
        $command    = $this->getCommand();
        if (!empty($command)) {
            $this->type = 'command';
        }

        $this->audio = isset($data['audio']) ? $data['audio'] : null;
        if (!empty($this->audio)) {
            $this->audio = new Audio($this->audio);
            $this->type  = 'Audio';
        }

        $this->document = isset($data['document']) ? $data['document'] : null;
        if (!empty($this->document)) {
            $this->document = new Document($this->document);
            $this->type     = 'Document';
        }

        $this->photo = isset($data['photo']) ? $data['photo'] : null; //array of photosize
        if (!empty($this->photo)) {
            foreach ($this->photo as $photo) {
                if (!empty($photo)) {
                    $photos[] = new PhotoSize($photo);
                }
            }
            $this->photo = $photos;
            $this->type  = 'Photo';
        }

        $this->sticker = isset($data['sticker']) ? $data['sticker'] : null;
        if (!empty($this->sticker)) {
            $this->sticker = new Sticker($this->sticker);
            $this->type    = 'Sticker';
        }

        $this->video = isset($data['video']) ? $data['video'] : null;
        if (!empty($this->video)) {
            $this->video = new Video($this->video);
            $this->type  = 'Video';
        }

        $this->voice = isset($data['voice']) ? $data['voice'] : null;
        if (!empty($this->voice)) {
            $this->voice = new Voice($this->voice);
            $this->type  = 'Voice';
        }

        $this->caption = isset($data['caption']) ? $data['caption'] : null;//string

        $this->contact = isset($data['contact']) ? $data['contact'] : null;
        if (!empty($this->contact)) {
            $this->contact = new Contact($this->contact);
        }

        $this->location = isset($data['location']) ? $data['location'] : null;
        if (!empty($this->location)) {
            $this->location = new Location($this->location);
            $this->type     = 'Location';
        }

        $this->venue = isset($data['venue']) ? $data['venue'] : null;
        if (!empty($this->venue)) {
            $this->venue = new Venue($this->venue);
            $this->type  = 'Venue';
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
            $this->type            = 'new_chat_member';
        }

        $this->left_chat_member = isset($data['left_chat_member']) ? $data['left_chat_member'] : null;
        if (!empty($this->left_chat_member)) {
            $this->left_chat_member = new User($this->left_chat_member);
            $this->type             = 'left_chat_member';
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
            $this->type           = 'new_chat_photo';
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

    /**
     * return the entire command like /echo or /echo@bot1 if specified
     *
     * @return string|void
     */
    public function getFullCommand()
    {
        if (substr($this->text, 0, 1) === '/') {
            $no_EOL   = strtok($this->text, PHP_EOL);
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

    /**
     * Get command
     *
     * @return bool|string
     */
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

    /**
     * Get message id
     *
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * Get User object related to the message
     *
     * @return \Longman\TelegramBot\Entities\User
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get date
     *
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get User object related to the  message
     *
     * @return \Longman\TelegramBot\Entities\Chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * Get User object related to the forwarded message
     *
     * @return \Longman\TelegramBot\Entities\User
     */
    public function getForwardFrom()
    {
        return $this->forward_from;
    }

    /**
     * Get User object related to the  message
     *
     * @return \Longman\TelegramBot\Entities\Chat
     */
    public function getForwardFromChat()
    {
        return $this->forward_from_chat;
    }

    /**
     * Get forward date
     *
     * @return mixed
     */
    public function getForwardDate()
    {
        return $this->forward_date;
    }

    /**
     * Get edit date
     *
     * @return mixed
     */
    public function getEditDate()
    {
        return $this->edit_date;
    }

    /**
     * Get reply to message
     *
     * @return \Longman\TelegramBot\Entities\ReplyToMessage
     */
    public function getReplyToMessage()
    {
        return $this->reply_to_message;
    }

    /**
     * Get text
     *
     * @param bool $without_cmd
     * @return string
     */
    public function getText($without_cmd = false)
    {
        $text = $this->text;

        if ($without_cmd && $command = $this->getFullCommand()) {
            if (strlen($command) + 1 < strlen($text)) {
                $text = substr($text, strlen($command) + 1);
            } else {
                $text = '';
            }
        }

        return $text;
    }

    /**
     * Get audio
     *
     * @return \Longman\TelegramBot\Entities\Audio
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * Get document
     *
     * @return \Longman\TelegramBot\Entities\Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Get photo
     *
     * @return array
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Get sticker
     *
     * @return \Longman\TelegramBot\Entities\Sticker
     */
    public function getSticker()
    {
        return $this->sticker;
    }

    /**
     * Get video
     *
     * @return \Longman\TelegramBot\Entities\Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Get voice
     *
     * @return \Longman\TelegramBot\Entities\Voice
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * Get caption
     *
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Get content
     *
     * @return \Longman\TelegramBot\Entities\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Get location
     *
     * @return \Longman\TelegramBot\Entities\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Get venue
     *
     * @return mixed
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Get new chat participant
     *
     * @return mixed
     */
    public function getNewChatParticipant()
    {
        return $this->new_chat_member;
    }

    /**
     * Get left chat participant
     *
     * @return mixed
     */
    public function getLeftChatParticipant()
    {
        return $this->left_chat_member;
    }

    /**
     * Get User object related to the new member
     *
     * @return \Longman\TelegramBot\Entities\User
     */
    public function getNewChatMember()
    {
        return $this->new_chat_member;
    }

    /**
     * Get User object related to the left member
     *
     * @return \Longman\TelegramBot\Entities\User
     */
    public function getLeftChatMember()
    {
        return $this->left_chat_member;
    }

    /**
     * Get new chat title
     *
     * @return mixed
     */
    public function getNewChatTitle()
    {
        return $this->new_chat_title;
    }

    /**
     * Get new chat photo
     *
     * @return mixed
     */
    public function getNewChatPhoto()
    {
        return $this->new_chat_photo;
    }

    /**
     * Get delete chat photo
     *
     * @return mixed
     */
    public function getDeleteChatPhoto()
    {
        return $this->delete_chat_photo;
    }

    /**
     * Get group chat created
     *
     * @return mixed
     */
    public function getGroupChatCreated()
    {
        return $this->group_chat_created;
    }

    /**
     * Get supergroup chat created
     *
     * @return mixed
     */
    public function getSupergroupChatCreated()
    {
        return $this->supergroup_chat_created;
    }

    /**
     * Get channel chat created
     *
     * @return mixed
     */
    public function getChannelChatCreated()
    {
        return $this->channel_chat_created;
    }

    /**
     * Get migrate to chat id
     *
     * @return mixed
     */
    public function getMigrateToChatId()
    {
        return $this->migrate_to_chat_id;
    }

    /**
     * Get migrate from chat id
     *
     * @return mixed
     */
    public function getMigrateFromChatId()
    {
        return $this->migrate_from_chat_id;
    }

    /**
     * Bot added in chat
     *
     * @return bool
     */
    public function botAddedInChat()
    {
        if (!empty($this->new_chat_member)) {
            if ($this->new_chat_member->getUsername() == $this->getBotName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get pinned message
     *
     * @return mixed
     */
    public function getPinnedMessage()
    {
        return $this->pinned_message;
    }

    /**
     * Get entities
     *
     * @return mixed
     */
    public function getEntities()
    {
        return $this->entities;
    }
}
