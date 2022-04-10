<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ShippingType extends Enum
{
    const PERSONAL_COURIER = "KURIR PRIBADI";
    const REGULER =   "REGULER";
    const SAME_CITY_DELIVERY = "PENGIRIMAN DALAM DAERAH";
}
