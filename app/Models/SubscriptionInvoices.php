<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class SubscriptionInvoices extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_invoices';

    protected $guarded = ['id'];

    protected $dates = [
        'date',
        'coupon_expire_at'
    ];

    public function scopeFilterInvoices(Builder $query, $request) {
        if($request->filled('search')) {
            $query->where('title', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('description', 'LIKE', '%'. $request->search .'%');
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
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
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
