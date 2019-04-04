<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffLoginNotificationsDetail extends Model
{
    //
protected $table = "staff_login_detail";
protected $fillable = ["staff_id","staff_name","time","day","month","year","admin_recieve_push"];

    
}
