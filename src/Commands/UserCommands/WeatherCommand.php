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

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TelegramLog;

/**
 * User "/weather" command
 */
class WeatherCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'weather';

    /**
     * @var string
     */
    protected $description = 'Show weather by location';

    /**
     * @var string
     */
    protected $usage = '/weather <location>';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Base URI for OpenWeatherMap API
     *
     * @var string
     */
    private $owm_api_base_uri = 'http://api.openweathermap.org/data/2.5/';

    /**
     * Get weather data using HTTP request
     *
     * @param string $location
     *
     * @return string
     */
    private function getWeatherData($location)
    {
        $client = new Client(['base_uri' => $this->owm_api_base_uri]);
        $path   = 'weather';
        $query  = [
            'q'     => $location,
            'units' => 'metric',
            'APPID' => trim($this->getConfig('owm_api_key')),
        ];

        try {
            $response = $client->get($path, ['query' => $query]);
        } catch (RequestException $e) {
            TelegramLog::error($e->getMessage());

            return '';
        }

        return (string) $response->getBody();
    }

    /**
     * Get weather string from weather data
     *
     * @param array $data
     *
     * @return string
     */
    private function getWeatherString(array $data)
    {
        try {
            if (!(isset($data['cod']) && $data['cod'] === 200)) {
                return '';
            }

            //http://openweathermap.org/weather-conditions
            $conditions     = [
                'clear'        => ' ☀️',
                'clouds'       => ' ☁️',
                'rain'         => ' ☔',
                'drizzle'      => ' ☔',
                'thunderstorm' => ' ⚡️',
                'snow'         => ' ❄️',
            ];
            $conditions_now = strtolower($data['weather'][0]['main']);

            return sprintf(
                'The temperature in %s (%s) is %s°C' . PHP_EOL .
                'Current conditions are: %s%s',
                $data['name'], //city
                $data['sys']['country'], //country
                $data['main']['temp'], //temperature
                $data['weather'][0]['description'], //description of weather
                isset($conditions[$conditions_now]) ? $conditions[$conditions_now] : ''
            );
        } catch (Exception $e) {
            TelegramLog::error($e->getMessage());

            return '';
        }
    }

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
        $text    = '';

        if (trim($this->getConfig('owm_api_key'))) {
            $location = trim($message->getText(true));
            if ($location !== '') {
                if ($weather_data = json_decode($this->getWeatherData($location), true)) {
                    $text = $this->getWeatherString($weather_data);
                }
                if ($text === '') {
                    $text = 'Cannot find weather for location: ' . $location;
                }
            } else {
                $text = 'You must specify location in format: /weather <city>';
            }
        } else {
            $text = 'OpenWeatherMap API key not defined.';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
