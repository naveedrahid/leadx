<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormKeyword extends Model
{
    use HasFactory;

    protected $table = 'form_keywords';

    protected $fillable = [
        'keyword',
        'created_by',
        'status',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function form()
    {
        return $this->belongsTo(CustomerForm::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
