<?php

namespace PhpTelegramBot\Core\Entities\InputMessageContent;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\LabeledPrice;

/**
 * @method string         getTitle()                     Product name, 1-32 characters
 * @method string         getDescription()               Product description, 1-255 characters
 * @method string         getPayload()                   Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use for your internal processes.
 * @method string|null    getProviderToken()             Optional. Payment provider token, obtained via @BotFather. Pass an empty string for payments in Telegram Stars.
 * @method string         getCurrency()                  Three-letter ISO 4217 currency code, see more on currencies. Pass “XTR” for payments in Telegram Stars.
 * @method LabeledPrice[] getPrices()                    Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in Telegram Stars.
 * @method int|null       getMaxTipAmount()              Optional. The maximum accepted amount for tips in the smallest units of the currency (integer, not float/double). For example, for a maximum tip of US$ 1.45 pass max_tip_amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0. Not supported for payments in Telegram Stars.
 * @method int[]|null     getSuggestedTipAmounts()       Optional. A JSON-serialized array of suggested amounts of tip in the smallest units of the currency (integer, not float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed max_tip_amount.
 * @method string|null    getProviderData()              Optional. A JSON-serialized object for data about the invoice, which will be shared with the payment provider. A detailed description of the required fields should be provided by the payment provider.
 * @method string|null    getPhotoUrl()                  Optional. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service.
 * @method int|null       getPhotoSize()                 Optional. Photo size in bytes
 * @method int|null       getPhotoWidth()                Optional. Photo width
 * @method int|null       getPhotoHeight()               Optional. Photo height
 * @method bool|null      getNeedName()                  Optional. Pass True if you require the user's full name to complete the order. Ignored for payments in Telegram Stars.
 * @method bool|null      getNeedPhoneNumber()           Optional. Pass True if you require the user's phone number to complete the order. Ignored for payments in Telegram Stars.
 * @method bool|null      getNeedEmail()                 Optional. Pass True if you require the user's email address to complete the order. Ignored for payments in Telegram Stars.
 * @method bool|null      getNeedShippingAddress()       Optional. Pass True if you require the user's shipping address to complete the order. Ignored for payments in Telegram Stars.
 * @method bool|null      getSendPhoneNumberToProvider() Optional. Pass True if the user's phone number should be sent to the provider. Ignored for payments in Telegram Stars.
 * @method bool|null      getSendEmailToProvider()       Optional. Pass True if the user's email address should be sent to the provider. Ignored for payments in Telegram Stars.
 * @method bool           isFlexible()                   Optional. Pass True if the final price depends on the shipping method. Ignored for payments in Telegram Stars.
 */
class InputInvoiceMessageContent extends InputMessageContent implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'prices' => [LabeledPrice::class],
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_flexible' => false,
        ];
    }
}
