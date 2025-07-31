<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'website_id',
        'form_id',
        'keywords',
        'is_blocked',
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_blocked' => 'boolean',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
