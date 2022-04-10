<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaymentStatus extends Enum
{
    const PAID   =   "PAID";
    const UNPAID =   "UNPAID";
    const CANCEL =   "CANCEL";
    const CONFIRMATION = "KONFIRMASI";
    const REJECTED = "DITOLAK";
}
