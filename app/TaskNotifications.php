<?php
//this is not just task notification but staff notifications in general
namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskNotifications extends Model
{
    //
    protected $table = "task_notifications";
    protected $fillable = ["task_id","task_header","staff_id","staff_name","viewed"];
    
}
