<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadBlockedIP extends Model
{
    use HasFactory;

    protected $table = 'lead_blocked_ips';

   protected $guarded = ['id'];

}
