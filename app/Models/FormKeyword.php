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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
