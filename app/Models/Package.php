<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    HasMany
};
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $guarded = ['id'];

    protected $appends = [
        'price',
        'format_duration',
        'has_subscriptions',
        'subscription_count',
        'free_plan'
    ];

    public function getFormatDurationAttribute() {
        $duration = ($this->duration > 1) ? $this->duration : 'Per';
        $duration .= ' ' . ucfirst($this->duration_type);
        return $duration;
    }

    public function getHasSubscriptionsAttribute()
    {
        return $this->subscriptions()->exists();
    }

    public function getSubscriptionCountAttribute()
    {
        return $this->subscriptions()->count();
    }

    public function getPriceAttribute()
    {
        if($this->sale_price) {
            return $this->sale_price;
        }

        return $this->regular_price;
    }

    public function getFreePlanAttribute()
    {
        return $this->regular_price == 0 ? true : false;
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'package_id', 'id');
    }

    public function payment_methods(): HasMany
    {
        return $this->hasMany(PackagePaymentMethod::class, 'package_id', 'id');
    }

    public function payment_links(): HasMany
    {
        return $this->hasMany(UserPaymentLink::class, 'user_id', 'id');
    }

    public function scopeFilterPackages(Builder $query, $request) {
        if($request->filled('search')) {
            $query->where('title', 'LIKE', '%'. $request->search .'%');
        }

        if($request->filled('duration_type')) {
            $query->where('duration_type', $request->duration_type);
        }

        if($request->filled('hide_private')) {
            $query->where('is_private', 0);
        }

        if($request->has('free_trial')) {
            if($request->get('free_trial') == "1") {
                $query->whereNotNull('trial_period_days');
            }

            if($request->get('free_trial') == "0") {
                $query->whereNull('trial_period_days');
            }
        }

        if($request->filled('website_limit')) {
            if($request->get('website_limit') == "1") {
                $query->whereNull('website_limit');
            }
            
            if($request->get('website_limit') == "0") {
                $query->whereNotNull('website_limit');
            }
        }

        if($request->filled('lead_limit')) {
            if($request->get('lead_limit') == "1") {
                $query->whereNull('lead_limit');
            }
            
            if($request->get('lead_limit') == "0") {
                $query->whereNotNull('lead_limit');
            }
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
}
