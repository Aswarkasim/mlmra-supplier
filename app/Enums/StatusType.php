<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StatusType extends Enum
{
    const ACTIVE = 'ACTIVE';
    const INACTIVE =   'INACTIVE';
    const DRAFT = 'DRAFT';
    const PUBLISHED = 'PUBLISHED';
    const ARCHIVED = 'ARCHIVED';
    const BLOCKED = "BLOCKED";

}
