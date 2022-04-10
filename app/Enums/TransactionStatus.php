<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TransactionStatus extends Enum
{
    const UNPAID    =   "UNPAID";
    const PROCESS   =   "PROCESS";
    const SENT      =   "SENT";
    const DONE      =   "DONE";
    const CANCEL    =   "CANCEL";
    const RETURNED  =   "RETURNED";
}
