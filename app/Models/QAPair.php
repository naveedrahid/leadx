<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QAPair extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qa_pairs';

    protected $casts = [
        'is_active' => 'bool',
        'priority' => 'int',
        'tags' => 'array',
        'last_used_at' => 'datetime'
    ];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }
}
