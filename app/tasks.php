<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tasks extends Model
{
    //
    protected $table = 'tasks';
    protected $fillable = ['task_header','users_assigned','task_content','attached_file','approval_status'];
}
