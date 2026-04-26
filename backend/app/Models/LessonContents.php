<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Concerns\HasInfo;

class lessonContents extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_id',
        'type',
        'content',
        'order',
    ];
}
