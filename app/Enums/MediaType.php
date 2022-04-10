<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MediaType extends Enum
{
    const IMAGE =  "IMAGE";
    const VIDEO =  "VIDEO";
    const PDF   =  "PDF";
}
