<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BankType extends Enum
{
    const MANDIRI = "MANDIRI";
    const BNI =   "BNI";
    const BRI = "BRI";
    const BTN = "BTN";
    const BCA = "BCA";
}
