<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthImage extends Model
{
    //
    protected $table = 'face_auth';
    protected $fillable = ["staff_name","staff_id","image"];
}
