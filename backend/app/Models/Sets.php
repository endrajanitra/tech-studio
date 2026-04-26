<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sets extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'course_id',
        'order',
    ];
}
