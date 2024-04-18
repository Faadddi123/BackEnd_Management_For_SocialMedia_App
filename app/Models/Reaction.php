<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    // Specify the table associated with the Reaction model
    protected $table = 'reactions';

    // Specify the fields that are mass assignable
    protected $fillable = ['name', 'icon'];
}
