<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/31
 * Time: 11:33
 */

namespace App\Rules;

use App\Inspections\Spam;

class SpamFree
{
    public function passes($attribute, $value)
    {
        try {
            return ! resolve(Spam::class)->detect($value);
        }catch (\Exception $exception){
            return false;
        }
    }
}