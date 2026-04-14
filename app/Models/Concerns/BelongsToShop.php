<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToShop
{
    protected static function bootBelongsToShop(): void
    {
        static::addGlobalScope('shop', function (Builder $builder): void {
            $tenantShopId = app()->bound('tenant.shop_id') ? app('tenant.shop_id') : null;
            $user = auth()->user();

            if ($tenantShopId) {
                $builder->where($builder->qualifyColumn('shop_id'), $tenantShopId);

                return;
            }

            if ($user instanceof User && ! $user->hasRole('super_admin') && $user->shop_id) {
                $builder->where($builder->qualifyColumn('shop_id'), $user->shop_id);
            }
        });

        static::creating(function ($model): void {
            if (! empty($model->shop_id)) {
                return;
            }

            $tenantShopId = app()->bound('tenant.shop_id') ? app('tenant.shop_id') : null;
            $userShopId = auth()->check() ? auth()->user()->shop_id : null;

            if ($tenantShopId || $userShopId) {
                $model->shop_id = $tenantShopId ?? $userShopId;
            }
        });
    }
}
