<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';

    protected $guarded = ['id'];

    protected $casts = [
        'form_data' => 'json',
        'is_viewed' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function lead_blocked_ip(): BelongsTo
    {
        return $this->belongsTo(LeadBlockedIP::class, 'id', 'lead_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id', 'id');
    }

    public function scopeFilterLeads(Builder $query, $request)
    {
        if ($request->filled('wpform_id')) {
            $query->where('wpform_id', $request->wpform_id);
        }

        if ($request->filled('website_id')) {
            $query->where('website_id', $request->website_id);
        }

        if ($request->filled('uuid')) {
            $query->where('uuid', $request->uuid);
        }

        if ($request->get('is_viewed') == "1") {
            $query->whereNotNull('is_viewed', 1);
        }

        if ($request->get('is_viewed') == "0") {
            $query->whereNotNull('is_viewed', 0);
        }

        if ($request->filled('status')) {
            if (gettype($request->status) == 'array') {
                $query->whereIn('status', $request->status);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->has('dates')) {
            $dateRange = $request->dates;
            $startDate = Carbon::parse($dateRange[0])->startOfDay();
            $endDate = Carbon::parse($dateRange[1])->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    }

    public function scopeByUser(Builder $query, $user_id)
    {
        $query->where('user_id', $user_id);
    }

    public function scopeStatus(Builder $query, $status)
    {
        if (gettype($status) == 'array') {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }
    }
}
