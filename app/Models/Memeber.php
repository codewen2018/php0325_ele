<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memeber extends Model
{
    //
    public $fillable=['username','password','tel'];


    public $hidden=['password','remember_token'];
}
