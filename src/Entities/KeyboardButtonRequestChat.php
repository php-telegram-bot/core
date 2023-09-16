<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class KeyboardButtonRequestChat
 *
 * This entity defines the criteria used to request a suitable chat. The identifier of the selected chat will be shared with the bot when the corresponding button is pressed.
 *
 * @link https://core.telegram.org/bots/api#keyboardbuttonrequestchat
 *
 * @method int getRequestId()                                   Signed 32-bit identifier of the request, which will be received back in the ChatShared object. Must be unique within the message
 * @method bool getChatIsChannel()                              Pass True to request a channel chat, pass False to request a group or a supergroup chat.
 * @method bool getChatIsForum()                                Optional. Pass True to request a forum supergroup, pass False to request a non-forum chat. If not specified, no additional restrictions are applied.
 * @method bool getChatHasUsername()                            Optional. Pass True to request a supergroup or a channel with a username, pass False to request a chat without a username. If not specified, no additional restrictions are applied.
 * @method bool getChatIsCreated()                              Optional. Pass True to request a chat owned by the user. Otherwise, no additional restrictions are applied.
 * @method ChatAdministratorRights getUserAdministratorRights() Optional. A JSON-serialized object listing the required administrator rights of the user in the chat. The rights must be a superset of bot_administrator_rights. If not specified, no additional restrictions are applied.
 * @method ChatAdministratorRights getBotAdministratorRights()  Optional. A JSON-serialized object listing the required administrator rights of the bot in the chat. The rights must be a subset of user_administrator_rights. If not specified, no additional restrictions are applied.
 * @method bool getBotIsMember()                                Optional. Pass True to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
 *
 * @method $this setRequestId(int $request_id)                                                  Signed 32-bit identifier of the request, which will be received back in the ChatShared object. Must be unique within the message
 * @method $this setChatIsChannel(bool $chat_is_channel)                                        Pass True to request a channel chat, pass False to request a group or a supergroup chat.
 * @method $this setChatIsForum(bool $chat_is_forum)                                            Optional. Pass True to request a forum supergroup, pass False to request a non-forum chat. If not specified, no additional restrictions are applied.
 * @method $this setChatHasUsername(bool $chat_has_username)                                    Optional. Pass True to request a supergroup or a channel with a username, pass False to request a chat without a username. If not specified, no additional restrictions are applied.
 * @method $this setChatIsCreated(bool $chat_is_created)                                        Optional. Pass True to request a chat owned by the user. Otherwise, no additional restrictions are applied.
 * @method $this setUserAdministratorRights(ChatAdministratorRights $user_administrator_rights) Optional. A JSON-serialized object listing the required administrator rights of the user in the chat. The rights must be a superset of bot_administrator_rights. If not specified, no additional restrictions are applied.
 * @method $this setBotAdministratorRights(ChatAdministratorRights $bot_administrator_rights)   Optional. A JSON-serialized object listing the required administrator rights of the bot in the chat. The rights must be a subset of user_administrator_rights. If not specified, no additional restrictions are applied.
 * @method $this setBotIsMember(bool $bot_is_member)                                            Optional. Pass True to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
 */
class KeyboardButtonRequestChat extends Entity
{

}
