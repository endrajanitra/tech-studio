<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lessons;
use App\Models\Courses;

class Sets extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'course_id',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lessons::class, 'set_id')->orderBy('order');
    }
}
