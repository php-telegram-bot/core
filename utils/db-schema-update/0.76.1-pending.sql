ALTER TABLE `message` ADD COLUMN `web_app_data` TEXT NULL DEFAULT NULL COMMENT 'Service message: data sent by a Web App' AFTER `voice_chat_participants_invited`;

ALTER TABLE `message`
    CHANGE `voice_chat_scheduled` `video_chat_scheduled` TEXT COMMENT 'Service message: video chat scheduled',
    CHANGE `voice_chat_started` `video_chat_started` TEXT COMMENT 'Service message: video chat started',
    CHANGE `voice_chat_ended` `video_chat_ended` TEXT COMMENT 'Service message: video chat ended',
    CHANGE `voice_chat_participants_invited` `video_chat_participants_invited` TEXT COMMENT 'Service message: new participants invited to a video chat';
