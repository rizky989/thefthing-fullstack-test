<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $hidden = ['password'];

    protected $guarded = ['id','created_at','updated_at'];
}
