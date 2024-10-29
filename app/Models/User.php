<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{
    HasOne,
    HasMany,
    BelongsToMany
};
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super' => 'boolean',
        'first_attempt' => 'boolean'
    ];

    protected $appends = [
        'fullname',
        'initials',
        'verified',
        'profile_image_url',
        'other'
    ];

    public function getOtherAttribute()
    {
        if($this->user_type == 'customer') {
            return [
                'is_subscribed' => $this->is_subscribed,
                'is_subscription_active' => $this->is_subscription_active,
                'has_exceeded_leads_limit' => $this->has_exceeded_leads_limit,
                'leads_count' => $this->leads_count
            ];
        }
    }

    public function getLeadsCountAttribute()
    {
        return $this->leads()->count();
    }

    public function getFullnameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }

    public function getVerifiedAttribute()
    {
        return !is_null($this->email_verified_at);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()->exists();
    }

    public function getIsSubscriptionActiveAttribute()
    {
        $active_subscription = $this->subscriptions()->status(['active', 'trialing'])->first();
        return !is_null($active_subscription);
    }

    public function getHasExceededLeadsLimitAttribute()
    {
        if($this->is_subscription_active) {
            $active_subscription = $this->subscriptions()->status(['active', 'trialing'])->first();
            $lead_limit = $active_subscription->package->lead_limit;
            return ($lead_limit != null) ? $active_subscription->leads >= $lead_limit : false;
            // return ($lead_limit != null) ? $this->leads()->count() >= $lead_limit : false;
        }
        
        return true;
    }

    public function getProfileImageUrlAttribute()
    {
        if($this->profile_image) {
            return asset('storage/users/' . $this->profile_image);
        }

        return null;
    }

    public function getInitialsAttribute()
    {
        $initials = '';
        $initials .= $this->first_name[0];
        $initials .= $this->last_name != '' ? $this->last_name[0] : '';
        return $initials;
    }

    public function customer_details(): HasOne
    {
        return $this->hasOne(CustomerDetails::class, 'user_id', 'id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    public function payment_links(): HasMany
    {
        return $this->hasMany(UserPaymentLink::class, 'user_id', 'id');
    }

    public function subscription_invoices(): HasMany
    {
        return $this->hasMany(SubscriptionInvoices::class, 'user_id', 'id');
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user', 'user_id', 'coupon_id');
    }

    public function payment_cards(): HasMany
    {
        return $this->hasMany(PaymentCard::class, 'user_id', 'id');
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class, 'user_id', 'id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'user_id', 'id');
    }

    public function license(): HasOne
    {
        return $this->hasOne(License::class, 'user_id', 'id');
    }

    public function scopeFilterUsers(Builder $query, $request) {
        if($request->filled('search')) {
            $query->where('first_name', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('last_name', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('email', $request->search);
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

    public function scopeCustomer(Builder $query) {
        $query->where('user_type', 'customer');
    }

    public function scopeAdmin(Builder $query) {
        $query->where('user_type', 'admin');
    }

    public function scopeSuper(Builder $query) {
        $query->where('is_super', true);
    }

    public function scopeStatus(Builder $query, $status) {
        if(gettype($status) == 'array') {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
