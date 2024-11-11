<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'file',
        'status',
        'creator_id'

    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id' );
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'assignments', 'task_id', 'user_id');
    }
}
