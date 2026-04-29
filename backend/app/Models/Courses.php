<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Sets;

class Courses extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_published',
    ];

    public function sets()
    {
        return $this->hasMany(Sets::class, 'course_id')->orderBy('order');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

}
