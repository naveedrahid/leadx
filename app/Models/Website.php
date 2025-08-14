<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany
};
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Website extends Model
{
    use HasFactory;

    protected $table = 'websites';

    protected $guarded = ['id'];

    protected $appends = [
        'has_subscriptions',
        'has_leads'
    ];

    public function getHasSubscriptionsAttribute()
    {
        return $this->subscriptions()->exists();
    }

    public function getHasLeadsAttribute()
    {
        return $this->leads()->exists();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class, 'subscriptions_websites', 'website_id', 'subscription_id');
    }

    public function leads(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'id', 'website_id');
    }

    public function scopeFilterWebsites(Builder $query, $request)
    {
        if($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        if($request->filled('website_name')) {
            $query->where('website_name', $request->website_name);
        }

        if($request->filled('website_url')) {
            $query->where('website_url', $request->website_url);
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

    public function scopeWebsiteUrl(Builder $query, $url) {
        $query->where('website_url', $url);
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
