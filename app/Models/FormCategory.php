<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    HasMany,
    BelongsTo,
    BelongsToMany
};
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class FormCategory extends Model
{
    use HasFactory;

    protected $table = 'form_categories';

    protected $guarded = ['id'];

    protected $appends = [
        'templates_count'
    ];

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(FormTemplate::class, 'template_categories', 'category_id', 'template_id');
    }

    public function parent_category() : BelongsTo
    {
        return $this->belongsTo(FormCategory::class, 'parent_id');
    }

    public function child_categories(): HasMany
    {
        return $this->hasMany(FormCategory::class, 'parent_id');
    }

    public function getTemplatesCountAttribute() {
        return $this->templates()->count();
    }

    public function scopeFilter(Builder $query, $request)
    {
        if($request->filled('search')) {
            $query->where('name', 'like', '%'. $request->search .'%');
        }

        if($request->filled('parent')) {
            $query->where('parent_id', $request->parent);
        }

        if($request->filled('hide_empty')) {
            $query->whereHas('templates');
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
