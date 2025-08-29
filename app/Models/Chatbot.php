<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chatbot extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'bool',
        'do_not_go_beyond' => 'bool',
        'show_logo' => 'bool',
        'show_datetime' => 'bool',
        'transparent_trigger' => 'bool',
        'moderation_on' => 'bool',
        'temperature' => 'float',
        'top_k' => 'int',
        'confidence_threshold' => 'float',
        'trigger_avatar_size' => 'int',
        'iframe_width' => 'int',
        'iframe_height' => 'int',
        'settings' => 'array',
        'domain_allowlist' => 'array',
    ];

    public function qaPairs()
    {
        return $this->hasMany(QAPair::class);
    }

    public static function makeUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'chatbot';
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) $slug = $base . '-' . $i++;
        return $slug;
    }
}
