<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/30
 * Time: 16:18
 */

namespace App\Inspections;


class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class,
    ];

    public function detect($body)
    {
        foreach ($this->inspections as $inspection){
            app($inspection)->detect($body);
        }

        return false;
    }
}