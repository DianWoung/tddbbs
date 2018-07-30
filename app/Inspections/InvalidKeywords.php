<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/30
 * Time: 16:51
 */

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    protected $keywords = [
        'something forbidden',
    ];

    public function detect($body)
    {
        foreach ($this->keywords as $invalidKeyword){
            if (stripos($body, $invalidKeyword) !== false){
                throw new Exception('Your reply contains spam');
            }
        }
    }
}