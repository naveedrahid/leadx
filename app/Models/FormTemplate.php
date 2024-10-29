<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsToMany,
    HasMany
};
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class FormTemplate extends Model
{
    use HasFactory;

    protected $table = 'form_template';

    protected $guarded = ['id'];

    protected $appends = [
        'template_image_url',
    ];

    public function getTemplateImageUrlAttribute()
    {
        if($this->template_image) {
            return asset('storage/templates/' . $this->template_image);
        }

        return asset('_app_assets/images/contact-form.png');
    }

    public function mail_settings(): HasMany
    {
        return $this->hasMany(FormMailSetting::class, 'form_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(FormCategory::class, 'template_categories', 'template_id', 'category_id');
    }

    public function scopeFilter(Builder $query, $request)
    {
        if($request->filled('search')) {
            $query->where('form_name', 'like', '%'. $request->search .'%');
        }

        if($request->filled('form_key')) {
            $query->where('form_key', $request->form_key);
        }

        if($request->filled('category')) {
            $query->whereHas('categories', function($subQuery) use ($request) {
                if(gettype($request->category) == 'array') {
                    $subQuery->whereIn('id', $request->category);
                } else {
                    $subQuery->where('id', $request->category);
                }
            });
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
