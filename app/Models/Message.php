<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'sources' => 'array',
        'meta' => 'array',
        'score' => 'float',
        'tokens_input' => 'int',
        'tokens_output' => 'int',
        'latency_ms' => 'int'
    ];
}
