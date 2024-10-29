<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class FeedBack extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $guarded = ['id'];

    public function scopeFilterFeedBack(Builder $query, $request)
    {
        if($request->filled('search')) {
            $query->where('name', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('email', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('subject', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('message', 'LIKE', '%'. $request->search .'%');
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
}
