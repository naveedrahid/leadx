<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsToMany,
    HasMany
};
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $guarded = ['id'];

    protected $dates = [
        'expires_at'
    ];

    protected $appends = [
        'discount',
        'has_users',
        'has_subscriptions',
        'subscription_count',
    ];

    public function getHasSubscriptionsAttribute()
    {
        return $this->subscriptions()->exists();
    }

    public function getSubscriptionCountAttribute()
    {
        return $this->subscriptions()->count();
    }

    public function getDiscountAttribute()
    {
        if($this->type == 'fixed') {
            return price_format($this->amount);
        }

        return $this->amount .'%';
    }

    public function getHasUsersAttribute()
    {
        return $this->users()->exists();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coupon_user', 'coupon_id', 'user_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'coupon_id', 'id');
    }

    public function scopeFilterCoupons(Builder $query, $request) {
        if($request->filled('search')) {
            $query->where('title', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('code', 'LIKE', '%'. $request->search .'%');
        }

        if($request->filled('status')) {
            if(gettype($request->status) == 'array') {
                $query->whereIn('status', $request->status);
            } else {
                $query->where('status', $request->status);
            }
        }

        if($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if($request->filled('duration')) {
            $query->where('duration', $request->duration);
        }

        if($request->has('dates')) {
            $dateRange = $request->dates;
            $startDate = Carbon::parse($dateRange[0])->startOfDay();
            $endDate = Carbon::parse($dateRange[1])->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    }

    public function scopeStatus(Builder $query, $status) {
        if(gettype($status) == 'array') {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }
    }

    public function scopeCode(Builder $query, $code) {
        $query->where('code', $code);
    }
}
