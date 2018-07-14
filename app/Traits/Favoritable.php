<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 2018/7/14
 * Time: 15:43
 */

namespace App\Traits;
use App\Favorite;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()){
            return $this->favorites()->create($attributes);
        }

    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}