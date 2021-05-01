<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\InputMessageContent;

use Longman\TelegramBot\Entities\InlineQuery\InlineEntity;

/**
 * Class InputTextMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputinvoicemessagecontent
 *
 * @method string         getTitle()                     Product name, 1-32 characters
 * @method string         getDescription()               Product description, 1-255 characters
 * @method string         getPayload()                   Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use for your internal processes.
 * @method string         getProviderToken()             Payment provider token, obtained via Botfather
 * @method string         getCurrency()                  Three-letter ISO 4217 currency code, see more on currencies
 * @method LabeledPrice[] getPrices()                    Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.)
 * @method int            getMaxTipAmount()              Optional. The maximum accepted amount for tips in the smallest units of the currency (integer, not float/double). For example, for a maximum tip of US$1.45 pass max_tip_amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0
 * @method int[]          getSuggestedTipAmounts()       Optional. A JSON-serialized array of suggested amounts of tip in the smallest units of the currency (integer, not float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed max_tip_amount.
 * @method string         getProviderData()              Optional. A JSON-serialized object for data about the invoice, which will be shared with the payment provider. A detailed description of the required fields should be provided by the payment provider.
 * @method string         getPhotoUrl()                  Optional. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service. People like it better when they see what they are paying for.
 * @method int            getPhotoSize()                 Optional. Photo size
 * @method int            getPhotoWidth()                Optional. Photo width
 * @method int            getPhotoHeight()               Optional. Photo height
 * @method bool           getNeedName()                  Optional. Pass True, if you require the user's full name to complete the order
 * @method bool           getNeedPhoneNumber()           Optional. Pass True, if you require the user's phone number to complete the order
 * @method bool           getNeedEmail()                 Optional. Pass True, if you require the user's email address to complete the order
 * @method bool           getNeedShippingAddress()       Optional. Pass True, if you require the user's shipping address to complete the order
 * @method bool           getSendPhoneNumberToProvider() Optional. Pass True, if user's phone number should be sent to provider
 * @method bool           getSendEmailToProvider()       Optional. Pass True, if user's email address should be sent to provider
 * @method bool           getIsFlexible()                Optional. Pass True, if the final price depends on the shipping method
 *
 * @method $this setTitle(string $title)                                           Product name, 1-32 characters
 * @method $this setDescription(string $description)                               Product description, 1-255 characters
 * @method $this setPayload(string $payload)                                       Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use for your internal processes.
 * @method $this setProviderToken(string $provider_token)                          Payment provider token, obtained via Botfather
 * @method $this setCurrency(string $currency)                                     Three-letter ISO 4217 currency code, see more on currencies
 * @method $this setPrices(LabeledPrice[] $prices)                                 Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.)
 * @method $this setMaxTipAmount(int $max_tip_amount)                              Optional. The maximum accepted amount for tips in the smallest units of the currency (integer, not float/double). For example, for a maximum tip of US$1.45 pass max_tip_amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0
 * @method $this setSuggestedTipAmounts(int[] $suggested_tip_amounts)              Optional. A JSON-serialized array of suggested amounts of tip in the smallest units of the currency (integer, not float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed max_tip_amount.
 * @method $this setProviderData(string $provider_data)                            Optional. A JSON-serialized object for data about the invoice, which will be shared with the payment provider. A detailed description of the required fields should be provided by the payment provider.
 * @method $this setPhotoUrl(string $photo_url)                                    Optional. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service. People like it better when they see what they are paying for.
 * @method $this setPhotoSize(int $photo_size)                                     Optional. Photo size
 * @method $this setPhotoWidth(int $photo_width)                                   Optional. Photo width
 * @method $this setPhotoHeight(int $photo_height)                                 Optional. Photo height
 * @method $this setNeedName(bool $need_name)                                      Optional. Pass True, if you require the user's full name to complete the order
 * @method $this setNeedPhoneNumber(bool $need_phone_number)                       Optional. Pass True, if you require the user's phone number to complete the order
 * @method $this setNeedEmail(bool $need_email)                                    Optional. Pass True, if you require the user's email address to complete the order
 * @method $this setNeedShippingAddress(bool $need_shipping_address)               Optional. Pass True, if you require the user's shipping address to complete the order
 * @method $this setSendPhoneNumberToProvider(bool $send_phone_number_to_provider) Optional. Pass True, if user's phone number should be sent to provider
 * @method $this setSendEmailToProvider(bool $send_email_to_provider)              Optional. Pass True, if user's email address should be sent to provider
 * @method $this setIsFlexible(bool $is_flexible)                                  Optional. Pass True, if the final price depends on the shipping method
 */
class InputInvoiceMessageContent extends InlineEntity implements InputMessageContent
{

}
