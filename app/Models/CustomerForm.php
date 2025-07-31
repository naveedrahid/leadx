<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'form_id',
        'form_name',
        'form_key',
        'website_id',
        'template',
        'custom_css',
        'settings',
        'messages',
        'description',
        'template_image',
        'status',
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
