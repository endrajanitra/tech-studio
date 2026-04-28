<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Concerns\HasInfo;
use App\Models\Lessons;
use App\Models\Options;

class lessonContents extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_id',
        'type',
        'content',
        'order',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lessons::class);
    }

    public function options()
    {
        return $this->hasMany(Options::class);
    }
}
