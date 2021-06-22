<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ToDoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'done', 'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
