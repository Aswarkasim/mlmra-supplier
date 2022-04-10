<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ActionType extends Enum
{
    const SAVE = 'SAVE';
    const PUBLISH = 'PUBLISH';
    const ARCHIVE = 'ARCHIVE';
}
