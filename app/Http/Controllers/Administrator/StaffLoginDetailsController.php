<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StaffLoginNotificationsDetail;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class StaffLoginDetailsController extends Controller
{
    protected $staff_login_details;
   protected $staffs;
    public function __construct()
    {
        $this->middleware("auth:admins");
        $this->staff_login_details = new StaffLoginNotificationsDetail;
        $this->staffs = new User;
        
    }
    //
   
    public function CurrentlyLoggedInStaffs()
    {
       // $time = date("H:i:s");
       // $day = date("l");
        $Month =  date("F");
        $year = date("Y");
        $day_of_month = date("j");
        $loggedInUsers = array();
         $notLoggedInUsers = array();
       $GetData = $this->staff_login_details::where(["date"=>$day_of_month,"month"=>$Month,"year"=>$year])->get();
       foreach ($GetData as $key => $value) {
           # code... 
          $name = $value->staff_name;
          $id = $value->id;
          $staff_id = $value->staff_id;
         $loggedIn = array("staff_name"=>$name, "id"=>$id,"staff_id"=>$staff_id);
        $data[] =  $loggedIn;
       }

       return $data;
  }


  public function getNotLoggedInUsers()
  {
    $Month =  date("F");
    $year = date("Y");
    $day_of_month = date("j");
    $getUsers = $this->staffs->get();
    $getNotLoggedInStaffs = array(); 
     $data = "";
    foreach ($getUsers as $key => $value) {
        # code...
    $staff_id = $value->id;
    $staff_name = $value->name;
    $last_login = $value->last_Login;
    $explode = explode(" ",$last_login);
    $date_data = $explode[0];
    $check_last_Login = strtotime($date_data);
    $day_data = date("j", $check_last_Login);
    $month_data = date("F",$check_last_Login);
    $year_data = date("Y",$check_last_Login);
    if($last_login==NULL){
        $data = array("staff_id"=>$staff_id,"staff_name"=>$staff_name);
        $getNotLoggedInStaffs[] = $data; 
    }else if(($last_login!=NULL) && (($day_of_month!==$day_data) || ($year!==$year_data) || ($Month!==$month_data) ) )
    {
        $data = array("staff_id"=>$staff_id,"staff_name"=>$staff_name);
        $getNotLoggedInStaffs[] = $data; 
        }

    }
 
    return $getNotLoggedInStaffs;

  }


  public function StaffLoginDetails()
  {
        $day = date("l");
       $Month =  date("F");
       $year = date("Y");
       $day_of_month = date("j");
      $loggedInUsers = $this->CurrentlyLoggedInStaffs();
      $notLoggedInUsers = $this->getNotLoggedInUsers();
     $data = array("logged_in"=>$loggedInUsers,"not_logged_in"=>$notLoggedInUsers,"day"=>$day,
     "month"=>$Month,"year"=>$year,"date"=>$day_of_month);
     return response()->json([
        "success"=>true,
        "data"=>$data
       ],200);  
  }

}
