<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StatusUser extends Enum
{
    const ACTIVE    =   "ACTIVE";
    const INACTIVE  =   "INACTIVE";
    const BLOCK     =   "BLOCK";
}
