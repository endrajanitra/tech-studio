<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sets;
use App\Models\lessonContents;

class Lessons extends Model
{
    use HasFactory;
    protected $fillable = [
        'set_id',
        'name',
        'order',
    ];

    public function set()
    {
        return $this->belongsTo(Sets::class);
    }

    public function contents()
    {
        return $this->hasMany(LessonContents::class)->orderBy('order');
    }

    public function completedByUsers()
    {
        return $this->belongsToMany(User::class, 'lesson_user');
    }
}
