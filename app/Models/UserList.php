<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserList extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'birthday' => 'date',
    ];
}
