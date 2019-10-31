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

/**
 * Class UserProfilePhotos
 *
 * @link https://core.telegram.org/bots/api#userprofilephotos
 *
 * @method int getTotalCount() Total number of profile pictures the target user has
 */
class UserProfilePhotos extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'photos' => PhotoSize::class,
        ];
    }

    /**
     * Requested profile pictures (in up to 4 sizes each)
     *
     * This method overrides the default getPhotos method and returns a nice array
     *
     * @return PhotoSize[][]
     */
    public function getPhotos()
    {
        $all_photos = [];

        if ($these_photos = $this->getProperty('photos')) {
            foreach ($these_photos as $photos) {
                $all_photos[] = array_map(function ($photo) {
                    return new PhotoSize($photo);
                }, $photos);
            }
        }

        return $all_photos;
    }
}
