<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lessons;
use App\Models\Options;

class LessonContents extends Model  
{
    use HasFactory;

    protected $table = 'lesson_contents'; 

    protected $fillable = [
        'lesson_id',
        'type',
        'content',
        'order',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }

    public function options()
    {
        return $this->hasMany(Options::class, 'lesson_content_id');
    }
}