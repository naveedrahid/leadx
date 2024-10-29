<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    HasMany
};
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $guarded = ['id'];

    protected $dates = [
        'next_billing_date',
        'coupon_expire_at',
        'trial_start_at',
        'trial_end_at',
        'start_at',
        'ended_at',
        'paused_at',
        'resumes_at'
    ];

    protected $cast = [
        'payload' => 'json'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(SubscriptionInvoices::class, 'subscription_id', 'id');
    }

    public function websites(): BelongsToMany
    {
        return $this->belongsToMany(Website::class, 'subscriptions_websites', 'subscription_id', 'website_id');
    }

    public function scopeFilterSubscriptions(Builder $query, $request) {
        if($request->filled('search')) {
            $query->where('name', 'LIKE', '%'. $request->search .'%');
        }

        if($request->filled('package')) {
            $query->where('package_id', $request->package);
        }

        if($request->filled('status')) {
            if(gettype($request->status) == 'array') {
                $query->whereIn('status', $request->status);
            } else {
                $query->where('status', $request->status);
            }
        }

        if($request->has('dates')) {
            $dateRange = $request->dates;
            $startDate = Carbon::parse($dateRange[0])->startOfDay();
            $endDate = Carbon::parse($dateRange[1])->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
    }

    public function scopeByUser(Builder $query, $user_id) {
        $query->where('user_id', $user_id);
    }

    public function scopeStatus(Builder $query, $status) {
        if(gettype($status) == 'array') {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }
    }
}
