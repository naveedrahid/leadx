<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Plugin extends Model
{
    use HasFactory;

    protected $table = 'plugins';

    protected $guarded = ['id'];

    protected $appends = [
        'plugin_file_url'
    ];

    public function getPluginFileUrlAttribute()
    {
        if($this->plugin_file) {
            return asset('storage/plugins/' . $this->plugin_file);
        }

        return null;
    }

    public function scopeFilterPlugins(Builder $query, $request) {
        if($request->filled('search')) {
            $query->where('plugin_name', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('plugin_url', 'LIKE', '%'. $request->search .'%');
            $query->orWhere('version', 'LIKE', '%'. $request->search .'%');
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
}
