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
use Longman\TelegramBot\Request;

/**
 * User "/weather" command
 */
class WeatherCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'weather';
    protected $description = 'Show weather by location';
    protected $usage = '/weather <location>';
    protected $version = '1.0.1';
    protected $public = true;
    /**#@-*/

    /**
     * Get weather using cURL request
     *
     * @param string $location
     *
     * @return object
     */
    private function getWeather($location)
    {
        $url = 'http://api.openweathermap.org/data/2.5/weather?q=' . $location . '&units=metric';

        $ch = curl_init();
        $curlConfig = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
        ];

        curl_setopt_array($ch, $curlConfig);
        $response = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code !== 200) {
            throw new \Exception('Error receiving data from url');
        }
        curl_close($ch);

        return $response;
    }

    /**
     * Get weather string
     *
     * @param string $location
     *
     * @return bool|string
     */
    private function getWeatherString($location)
    {
        if (empty($location)) {
            return false;
        }

        try {
            $data = $this->getWeather($location);

            $decode = json_decode($data, true);
            if (empty($decode) || $decode['cod'] != 200) {
                return false;
            }

            $city = $decode['name'];
            $country = $decode['sys']['country'];
            $temp = 'The temperature in ' . $city . ' (' . $country . ') is ' . $decode['main']['temp'] . '°C';
            $conditions = 'Current conditions are: ' . $decode['weather'][0]['description'];

            switch (strtolower($decode['weather'][0]['main'])) {
                case 'clear':
                    $conditions .= ' ☀';
                    break;

                case 'clouds':
                    $conditions .= ' ☁☁';
                    break;

                case 'rain':
                    $conditions .= ' ☔';
                    break;

                case 'thunderstorm':
                    $conditions .= ' ☔☔☔☔';
                    break;
            }

            $result = $temp . "\n" . $conditions;
        } catch (\Exception $e) {
            $result = '';
        }

        return $result;
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
            $text = 'You must specify location in format: /weather <city>';
        } else {
            $weather = $this->getWeatherString($text);
            if (empty($weather)) {
                $text = 'Can not find weather for location: ' . $text;
            } else {
                $text = $weather;
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
