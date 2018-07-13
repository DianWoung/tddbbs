<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/13
 * Time: 14:33
 */

namespace App\Filters;

use App\User;

class ThreadsFilters extends Filters
{
    protected $filters = ['by'];


    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrfail();

        return $this->builder->where('user_id', $user->id);
    }
}