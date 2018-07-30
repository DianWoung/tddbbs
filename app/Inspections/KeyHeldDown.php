<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/30
 * Time: 16:51
 */

namespace App\Inspections;


class KeyHeldDown
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/',$body)){
            throw new \Exception('Your reply contains spam.');
        }
    }
}