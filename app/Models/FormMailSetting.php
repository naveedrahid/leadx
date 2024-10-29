<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

class FormMailSetting extends Model
{
    use HasFactory;

    protected $table = 'form_mail_settings';

    protected $guarded = ['id'];

    public function form() : BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_id');
    }
}
