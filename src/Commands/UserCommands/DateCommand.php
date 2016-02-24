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

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

/**
 * User "/date" command
 */
class DateCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'date';
    protected $description = 'Show date/time by location';
    protected $usage = '/date <location>';
    protected $version = '1.2.1';
    protected $public = true;
    /**#@-*/

    /**
     * Base URL for Google Maps API
     *
     * @var string
     */
    private $base_url = 'https://maps.googleapis.com/maps/api';

    /**
     * Date format
     *
     * @var string
     */
    private $date_format = 'd-m-Y H:i:s';

    /**
     * Get coordinates
     *
     * @param string $location
     *
     * @return array|boolean
     */
    private function getCoordinates($location)
    {
        $url = $this->base_url . '/geocode/json?';
        $params = 'address=' . urlencode($location);

        $google_api_key = $this->getConfig('google_api_key');
        if (!empty($google_api_key)) {
            $params .= '&key=' . $google_api_key;
        }

        $data = $this->request($url . $params);
        if (empty($data)) {
            return false;
        }

        $data = json_decode($data, true);
        if (empty($data)) {
            return false;
        }

        if ($data['status'] !== 'OK') {
            return false;
        }

        $lat = $data['results'][0]['geometry']['location']['lat'];
        $lng = $data['results'][0]['geometry']['location']['lng'];
        $acc = $data['results'][0]['geometry']['location_type'];
        $types = $data['results'][0]['types'];

        return [$lat, $lng, $acc, $types];
    }

    /**
     * Get date
     *
     * @param string $lat
     * @param string $lng
     *
     * @return array|boolean
     */
    private function getDate($lat, $lng)
    {
        $url = $this->base_url . '/timezone/json?';

        $date_utc = new \DateTime(null, new \DateTimeZone("UTC"));

        $timestamp = $date_utc->format('U');

        $params = 'location=' . urlencode($lat) . ',' . urlencode($lng) . '&timestamp=' . urlencode($timestamp);

        $google_api_key = $this->getConfig('google_api_key');
        if (!empty($google_api_key)) {
            $params .= '&key=' . $google_api_key;
        }

        $data = $this->request($url . $params);
        if (empty($data)) {
            return false;
        }

        $data = json_decode($data, true);
        if (empty($data)) {
            return false;
        }

        if ($data['status'] !== 'OK') {
            return false;
        }

        $local_time = $timestamp + $data['rawOffset'] + $data['dstOffset'];

        return [$local_time, $data['timeZoneId']];
    }

    /**
     * Get formatted date
     *
     * @param string $location
     *
     * @return string
     */
    private function getFormattedDate($location)
    {
        if (empty($location)) {
            return 'The time in nowhere is never';
        }

        list($lat, $lng, $acc, $types) = $this->getCoordinates($location);

        if (empty($lat) || empty($lng)) {
            return 'It seems that in "' . $location . '" they do not have a concept of time.';
        }

        list($local_time, $timezone_id) = $this->getDate($lat, $lng);

        $date_utc = new \DateTime(gmdate('Y-m-d H:i:s', $local_time), new \DateTimeZone($timezone_id));

        return 'The local time in ' . $timezone_id . ' is: ' . $date_utc->format($this->date_format);
    }

    /**
     * Perform a simple cURL request
     *
     * @param string $url
     *
     * @return object
     */
    private function request($url)
    {
        $ch = curl_init();
        $curlConfig = [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true];

        curl_setopt_array($ch, $curlConfig);
        $response = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code !== 200) {
            throw new TelegramException('Error receiving data from url');
        }
        curl_close($ch);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);

        if (empty($text)) {
            $text = 'You must specify location in format: /date <city>';
        } else {
            $date = $this->getformattedDate($text);
            if (empty($date)) {
                $text = 'Can not find date for location: ' . $text;
            } else {
                $text = $date;
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
