<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/13
 * Time: 13:44
 */

namespace App\Http\ViewComposers;

use App\Channel;
use Illuminate\View\View;

class ChannelComposer
{
    protected $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function compose(View $view)
    {
        $channels = \Cache::rememberForever('channels', function (){
           return Channel::all();
        });
        $view->with('channels', $channels);
    }
}