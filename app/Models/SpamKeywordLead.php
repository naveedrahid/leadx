<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpamKeywordLead extends Model
{
    use HasFactory;

    protected  $table = 'spam_leads';

    protected $guarded = ['id'];
    protected $casts = [
        "found_keywords" => "array"
    ];
}
