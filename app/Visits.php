<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/8/3
 * Time: 11:58
 */

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?: 0;
    }

    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }

}