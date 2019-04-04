<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StaffLoginNotificationsDetail;

class StaffLoginDetailsController extends Controller
{
    protected $staff_login_details;
    
    public function __construct()
    {
        $this->staff_login_details = new StaffLoginNotificationsDetail;
    }
    //
}
