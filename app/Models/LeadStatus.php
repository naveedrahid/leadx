<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'status',
    ];

    public function leads(){
        return $this->hasMany(Lead::class, 'lead_status_id');
    }
}
