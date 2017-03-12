<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 */
class HelpCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $description = 'Show bot commands help';

    /**
     * @var string
     */
    protected $usage = '/help or /help <command>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $message_id = $message->getMessageId();
        $command    = trim($message->getText(true));

        //Only get enabled Admin and User commands
        /** @var Command[] $command_objs */
        $command_objs = array_filter($this->telegram->getCommandsList(), function ($command_obj) {
            /** @var Command $command_obj */
            return !$command_obj->isSystemCommand() && $command_obj->isEnabled();
        });

        //If no command parameter is passed, show the list
        if ($command === '') {
            $text = sprintf(
                '%s v. %s' . PHP_EOL . PHP_EOL . 'Commands List:' . PHP_EOL,
                $this->telegram->getBotName(),
                $this->telegram->getVersion()
            );

            foreach ($command_objs as $command) {
                if ($command->showInHelp()) {
                    continue;
                }

                $text .= sprintf(
                    '/%s - %s' . PHP_EOL,
                    $command->getName(),
                    $command->getDescription()
                );
            }

            $text .= PHP_EOL . 'For exact command help type: /help <command>';
        } else {
            $command = str_replace('/', '', $command);
            if (isset($command_objs[$command])) {
                /** @var Command $command_obj */
                $command_obj = $command_objs[$command];
                $text = sprintf(
                    'Command: %s v%s' . PHP_EOL .
                    'Description: %s' . PHP_EOL .
                    'Usage: %s',
                    $command_obj->getName(),
                    $command_obj->getVersion(),
                    $command_obj->getDescription(),
                    $command_obj->getUsage()
                );
            } else {
                $text = 'No help available: Command /' . $command . ' not found';
            }
        }

        $data = [
            'chat_id'             => $chat_id,
            'reply_to_message_id' => $message_id,
            'text'                => $text,
        ];

        return Request::sendMessage($data);
    }
}
