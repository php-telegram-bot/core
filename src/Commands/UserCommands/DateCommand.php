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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
    protected $version = '1.3.0';
    /**#@-*/

    /**
     * Guzzle Client object
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Base URI for Google Maps API
     *
     * @var string
     */
    private $google_api_base_uri = 'https://maps.googleapis.com/maps/api/';

    /**
     * The Google API Key from the command config
     *
     * @var string
     */
    private $google_api_key;

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
        $path = 'geocode/json';
        $query = ['address' => urlencode($location)];

        if ($this->google_api_key !== null) {
            $query['key'] = $this->google_api_key;
        }

        try {
            $response = $this->client->get($path, ['query' => $query]);
        } catch (RequestException $e) {
            throw new TelegramException($e->getMessage());
        }

        if (!($result = $this->validateResponseData($response->getBody()))) {
            return false;
        }

        $result = $result['results'][0];
        $lat = $result['geometry']['location']['lat'];
        $lng = $result['geometry']['location']['lng'];
        $acc = $result['geometry']['location_type'];
        $types = $result['types'];

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
        $path = 'timezone/json';

        $date_utc = new \DateTime(null, new \DateTimeZone('UTC'));
        $timestamp = $date_utc->format('U');

        $query = [
            'location'  => urlencode($lat) . ',' . urlencode($lng),
            'timestamp' => urlencode($timestamp)
        ];

        if ($this->google_api_key !== null) {
            $query['key'] = $this->google_api_key;
        }

        try {
            $response = $this->client->get($path, ['query' => $query]);
        } catch (RequestException $e) {
            throw new TelegramException($e->getMessage());
        }

        if (!($result = $this->validateResponseData($response->getBody()))) {
            return false;
        }

        $local_time = $timestamp + $result['rawOffset'] + $result['dstOffset'];

        return [$local_time, $result['timeZoneId']];
    }

    /**
     * Evaluate the response data and see if the request was successful
     *
     * @param string $data
     *
     * @return bool|array
     */
    private function validateResponseData($data)
    {
        if (empty($data)) {
            return false;
        }

        $data = json_decode($data, true);
        if (empty($data)) {
            return false;
        }

        if (isset($data['status']) && $data['status'] !== 'OK') {
            return false;
        }

        return $data;
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
     * {@inheritdoc}
     */
    public function execute()
    {
        //First we set up the necessary member variables.
        $this->client = new Client(['base_uri' => $this->google_api_base_uri]);
        if (($this->google_api_key = trim($this->getConfig('google_api_key'))) === '') {
            $this->google_api_key = null;
        }

        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $location = $message->getText(true);

        if (empty($location)) {
            $text = 'You must specify location in format: /date <city>';
        } else {
            $text = $this->getformattedDate($location);
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
