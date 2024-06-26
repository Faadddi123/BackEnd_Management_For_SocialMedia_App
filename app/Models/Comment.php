<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['text', 'user_id', 'displayed_id' ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $visible = ['id', 'text', 'user_id', 'displayed_id', 'created_at' ,'user'];
    // protected $appends = ['created_at'];
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
