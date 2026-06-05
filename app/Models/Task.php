<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'latitude',
        'longitude',
        'user_id',
        'scheduled_at',
        'is_done',
        'niveleducativo',
    ];
}