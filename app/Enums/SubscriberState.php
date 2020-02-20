<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * Class SubscriberState
 * @method static self ACTIVE()
 * @method static self BOUNCED()
 * @method static self JUNK()
 * @method static self UNCONFIRMED()
 * @method static self UNSUBSCRIBED()
 */
class SubscriberState extends Enum
{
}
