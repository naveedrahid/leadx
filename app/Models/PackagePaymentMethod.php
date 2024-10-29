<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

class PackagePaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'packages_payment_methods';

    protected $guarded = ['id'];

    public function package(): BelongsTo 
    {
        return $this->belongsTo(Package::class);
    }

    public function scopeStripe(Builder $query) 
    {
        $query->where('payment_method', 'stripe');
    }
}