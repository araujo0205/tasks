<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
@mixin \Illuminate\Database\Eloquent\Model
@phpstan-require-extends \Illuminate\Database\Eloquent\Model
@method static void addGlobalScope($scope, $implementation = null)
@method static void creating($callback)
 */
trait ScopedToUser
{
    protected static function bootScopedToUser()
    {
        static::addGlobalScope('user_scope', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('user_id', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }
}
