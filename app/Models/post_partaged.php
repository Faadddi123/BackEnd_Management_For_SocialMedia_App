<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post_partaged extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_partaged';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['text', 'post_id'];

    /**
     * The relationships to always eager-load.
     *
     * @var array
     */
    protected $with = ['post'];

    /**
     * Get the post that owns the post_partaged.
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id');
    }
}
