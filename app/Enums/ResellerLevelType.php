<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ResellerLevelType extends Enum
{
    const PEMULA =   "PEMULA";
    const MIDDLE =   "MIDDLE";
    const EXPERT = "EXPERT";
}
