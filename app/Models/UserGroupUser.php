<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroupUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_group_id'];

    public $timestamps = false;
}
