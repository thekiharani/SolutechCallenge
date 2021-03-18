<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasAuthor
{
    public static function bootHasAuthor()
    {
        if (auth()->check()) {
            // add user_id colum by default
            static::creating(function (Model $model) {
                $model->user_id = auth()->id();
            });

            // scope query to authenticated user
            static::addGlobalScope('user_id', function (Builder $builder) {
                return $builder->where('user_id', auth()->id());
            });
        }
    }
}
