<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ContentType extends Enum
{
    const MAIN_CONTENT  =   'MAIN_CONTENT';
    const FEATURED      =   'FEATURED';
    const PROFIT    =   'PROFIT';
    const FEATURED_IMAGE =  'FEATURED_IMAGE';
}
