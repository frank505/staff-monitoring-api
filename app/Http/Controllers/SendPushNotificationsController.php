<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CurlHttpHelperController;
use App\StaffLoginNotificationsDetail;
use Illuminate\Routing\UrlGenerator;
use App\admin;
class SendPushNotificationsController extends Controller
{
    protected $curl_helper;
    protected $staff_Login_details;
    protected $base_url;
    protected $admin;
    public function __construct(UrlGenerator $url)
    {
      $this->curl_helper = new CurlHttpHelperController;
      $this->staff_login_details = new StaffLoginNotificationsDetail; 
      $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
      $this->admin = new admin;
    }
    //

 



  public function getAdmin()
  {
  return $this->admin->get();
  }


    public function AdminGetUserLoginPush()
    {
        $method = "POST";
         $url = "https://fcm.googleapis.com/fcm/send";
        $administrators = $this->getAdmin();                   
            $getDetails = $this->staff_login_details::where(["admin_recieve_push"=>0])->OrderBy("id","DESC")->get();
            foreach ($getDetails as $key => $value) {
               $id = $value->id;
               $staff_name = $value->staff_name;

         
               $data = array(
                   "notification"=>array(
                   "title"=>"$staff_name Login Time",
                   "body"=>"$staff_name just logged in few seconds ago ",
                   "sound"=>"default",
                   "click_action"=>"FCM_PLUGIN_ACTIVITY",
                   "icon"=> $this->base_url."/push_images"."/logo_css.png", 
                   ),
                   "data"=>array(
                       "id"=>$id,
                       "staff_name"=>$staff_name
                   ),
                   "to"=>"/topics/all", 
                   "priority"=>"high",
                   "restricted_package_name"=>""
             );
             $data = json_encode($data);  
             CurlHelperController::perform_http_request($method, $url, $data);
            }

           
   
   
   
    }
   



}
