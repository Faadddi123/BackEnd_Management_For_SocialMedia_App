<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_text',
        'element_type',
        'element_path',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
