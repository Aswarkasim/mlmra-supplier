<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SentStatus extends Enum
{
    const PROCESS      =   "PROCESS";
    const SENDING      =   "SENDING";
    const SENT         =   "SENT";
    const RETURNED     =   "RETURNED";
}
