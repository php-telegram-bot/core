<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\Payments;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class ShippingOption
 *
 * This object represents one shipping option.
 *
 * @link https://core.telegram.org/bots/api#shippingoption
 *
 * @method string getId()    Shipping option identifier
 * @method string getTitle() Option title
 **/
class ShippingOption extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'prices' => LabeledPrice::class,
        ];
    }

    /**
     * List of price portions
     *
     * This method overrides the default getPrices method and returns a nice array
     *
     * @return LabeledPrice[]
     */
    public function getPrices()
    {
        $all_prices = [];

        if ($these_prices = $this->getProperty('prices')) {
            foreach ($these_prices as $prices) {
                $new_prices = [];
                foreach ($prices as $price) {
                    $new_prices[] = new LabeledPrice($price);
                }
                $all_prices[] = $new_prices;
            }
        }

        return $all_prices;
    }
}
