<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PaymentCard extends Model
{
    use HasFactory;

    protected $table = 'payment_cards';

    protected $guarded = ['id'];

    public function scopeFilterPaymentCards(Builder $query, $request) {
        if($request->filled('search')) {
            $query->where('brand', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('last4', 'LIKE', '%'. $request->search .'%');
        }

        if($request->filled('unexpired')) {
            $currentYear = now()->year;
            $currentMonth = now()->month;
            $query->where('exp_year', '>', $currentYear);
            $query->orWhere(function ($subQuery) use ($currentYear, $currentMonth) {
                $subQuery->where('exp_year', '=', $currentYear);
                $subQuery->where('exp_month', '>=', $currentMonth);
            });
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

    public function scopeUnexpired(Builder $query) {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $query->where('exp_year', '>', $currentYear);
        $query->orWhere(function ($subQuery) use ($currentYear, $currentMonth) {
            $subQuery->where('exp_year', '=', $currentYear);
            $subQuery->where('exp_month', '>=', $currentMonth);
        });
    }
}
