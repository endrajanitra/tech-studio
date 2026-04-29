<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_content_id',
        'option_text',
        'is_correct',
    ];

    public function content()
    {
        return $this->belongsTo(LessonContents::class, 'lesson_content_id');
    }
}