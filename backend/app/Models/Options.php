<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Concerns\HasInfo;

class Options extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_content_id',
        'option_text',
        'is_correct',
    ];
}
