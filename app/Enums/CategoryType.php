<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CategoryType extends Enum
{
    const PRODUCT  =  "PRODUCT";
    const SUPPLIER =  "SUPPLIER";
    const RESELLER =  "RESELLER";
    const CUSTOMER =  "CUSTOMER";
    const ARTICLE  =   "ARTICLE";
    const CATEGORY =  "CATEGORY";
    const PROFILE  =   "PROFILE";
    const MAIN_CONTENT = "MAIN_CONTENT";
    const FEATURED = "FEATURED";
    const PROFIT = "PROFIT";
    const FEATURED_IMAGE =  'FEATURED_IMAGE';
    const NOTIFICATION = "NOTIFICATION";
    const PAYMENT = "PAYMENT";
    const BRAND = "BRAND";
}
