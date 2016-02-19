<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot;

use Longman\TelegramBot\Command;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TrackingDB;
use Longman\TelegramBot\Entities\Update;

/**
 * Class Tracking
 */
class Tracking
{
    /**
     * Track has been fetched true false
     *
     * @var bool
     */
    protected $is_fetched = false;

    /**
     * All information fetched from the database
     *
     * @var array
     */
    protected $track = null;

    /**
     * Data stored inside the track
     *
     * @var array
     */
    protected $data = null;

    /**
     * Telegram user id
     *
     * @var int
     */
    protected $user_id;

    /**
     * Telegram chat_id
     *
     * @var int
     */
    protected $chat_id;

    /**
     * Group name let you share the session among commands
     * Call this as the same name of the command if you don't need to share the tracking
     *
     * @var strint
     */
    protected $group_name;

    /**
     * Command to be execute if the track is active
     *
     * @var string
     */
    protected $command;

    /**
     * Tracking contructor initialize a new track
     *
     * @param int    $user_id
     * @param int    $chat_id
     * @param string $name
     * @param string $command
     */
    public function __construct($user_id, $chat_id, $group_name = null, $command = null)
    {
        if (is_null($command)) {
            $command = $group_name;
        }

        $this->user_id = $user_id;
        $this->chat_id = $chat_id;
        $this->command = $command;
        $this->group_name = $group_name;
    }

    /**
     * Check if the track already exist
     *
     * @return bool
     */
    protected function trackExist()
    {
        //Track info already fetched
        if ($this->is_fetched) {
            return true;
        }

        $track = TrackingDB::selectTrack($this->user_id, $this->chat_id, 1);
        $this->is_fetched = true;

        if (isset($track[0])) {
            //Pick only the first element
            $this->track = $track[0];

            if (is_null($this->group_name)) {
                //Track name and command has not been specified. command has to be retrieved
                return true;
            }

            //a track with the same name was already opened store the data
            if ($this->track['track_name'] == $this->group_name) {
                $this->data = json_decode($this->track['data'], true);
                return true;
            }

            //a track with a differet name has been opened unsetting the DB one and reacreatea a new one
            TrackingDB::updateTrack(['is_active' => 0], ['chat_id' => $this->chat_id, 'user_id' => $this->user_id]);
            return false;
        }

        $this->track = null;
        return false;
    }

    /**
     * Check if the tracke already exist
     *
     * Check if a track has already been created in the database. If the track is not found, a new track is created. startTrack fetch the data stored in the database
     *
     * @return bool
     */
    public function startTrack()
    {
        if (!$this->trackExist()) {
            $status = TrackingDB::insertTrack($this->command, $this->group_name, $this->user_id, $this->chat_id);
            $this->is_fetched = true;
        }
        return true;
    }

    /**
     * Store the array/variable in the database with json_encode() function
     *
     * @param array $data
     */
    public function updateTrack($data)
    {
        //track must exist!
        if ($this->trackExist()) {
            $fields['data'] = json_encode($data);

            TrackingDB::updateTrack($fields, ['chat_id' => $this->chat_id, 'user_id' => $this->user_id]);
            //TODO verify query success before convert the private var
            $this->data = $data;
        }
    }

    /**
     * Delete the track from the database
     *
     * Currently the Track is not deleted but just unsetted
     *
     * @TODO should return something
     *
     * @param array $data
     */
    public function stopTrack()
    {
        if ($this->trackExist()) {
            TrackingDB::updateTrack(['is_active' => 0], ['chat_id' => $this->chat_id, 'user_id' => $this->user_id]);
        }
    }

    /**
     * Retrieve the command to execute from the track
     *
     * @param string
     */
    public function getTrackCommand()
    {
        if ($this->trackExist()) {
            return $this->track['track_command'];
        }
        return null;
    }

    /**
     * Retrive the data store in the track
     *
     * @param array $data
     */
    public function getData()
    {
        return $this->data;
    }
}
