<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFriends extends Model
{
    use HasFactory;


        protected $fillable = [
        'friend_userid',
        'friend_requestid',
        'friend_type',
        'friend_status',
        'friend_block_type',
    
    ];

}
