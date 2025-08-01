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
        'keywords',
        'form_id',
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

    public function form()
    {
        return $this->belongsTo(CustomerForm::class);
    }

    public function keywords_detail()
    {
        return FormKeyword::whereIn('id', $this->keywords)->get();
    }

    // public function keywordItems()
    // {
    //     return $this->hasMany(FormKeyword::class, 'id', 'keywords');
    // }
    public function getKeywordsDetailAttribute()
    {
        return FormKeyword::whereIn('id', $this->keywords)->get();
    }

    public function keywords()
    {
        return $this->belongsToMany(FormKeyword::class, 'block_keyword_form_keyword', 'block_keyword_id', 'form_keyword_id');
    }
}
