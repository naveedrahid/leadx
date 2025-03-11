<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSettingKeyword extends Model
{
    use HasFactory;

    protected $table = 'form_setting_keywords';

    protected $guarded = ['id'];

    protected $casts = [
        "keyword" => "array"
    ];
}
