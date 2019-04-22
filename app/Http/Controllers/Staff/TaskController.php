<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Http\Controllers\SanitizeController;
use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\tasks;

class TaskController extends Controller
{
    //
    protected $tasks;
   public function __construct()
   {
    $this->middleware("auth:staffs");
    $this->tasks = new tasks;
   }

   public function loadTasks($token)
   {
       $content = array();
       $final_data = array();
    $staff = auth("staffs")->authenticate($token);
    $staffName = $staff->name;
    $staffId = $staff->id;
    $getTasks = $this->tasks->get();
    foreach ($getTasks as $key => $value) {
    $time = $value->created_at;
    $explode = explode(" ",$time);
    $date_data = $explode[0];
    $time_task_creation = strtotime($date_data);
    $day_data = date("j", $time_task_creation);
    $month_data = date("F",$time_task_creation);
    $year_data = date("Y",$time_task_creation);
    $day_of_month = date("j");//should look like this 23rd the date("S") is for the rd  appended;
    $day = date("l");
    $Month = date("F");
    $Year = date("Y");

   if( ( ($day_of_month==$day_data) && ($Year==$year_data) && ($Month==$month_data) ) )
    { 
        $users_assigned = json_decode($value->users_assigned);
            $content = $users_assigned;
                  $final_data[] = array("users_assigned"=>$content,"id"=>$value->id,
            "task_header"=>$value->task_header,"task_content"=>$value->task_content,
        "attached_file"=>$value->attached_file); 
         }
    }
    
     $final_returned_data = array("name"=>$staffName,"id"=>$staffId,"data"=> $final_data,
    "day"=>$day,"month"=>$Month,"year"=>$Year,"day_of_month"=>$day_of_month);
   return $final_returned_data;
   }



     public function FullTaskDetails($token,$id)
     {
        $tasks = $this->tasks->find($id);
 
        if (!$tasks) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this task doesnt exist anymore '
            ], 400);
        }
        //get current staff name and staff id
        $staff = auth("staffs")->authenticate($token);
        $staffName = $staff->name;
        $staffId = $staff->id;
         $task_header = $tasks->task_header;
         $task_content = $tasks->task_content;
         $attached_file = $tasks->attached_file;
         $staffs = json_decode($tasks->users_assigned);
        $data = array("staff_name"=>$staffName,"id"=>$staffId,"task_content"=>$task_content, 
           "task_header"=>$task_header,"attached_file"=>$attached_file,"parse_options"=>$staffs,
           "task_data"=>$tasks);
        return response()->json([
            'success' => true,
            'data'=>$data
          ], 200);

     }

    
    public function LoadSearchData($token,$date)
     { 
         //get total tasks
         $final_data = array();
         $content = array();
         $staff = auth("staffs")->authenticate($token);
         $staffName = $staff->name;
         $staffId = $staff->id;
         $search_date_strtotime = strtotime($date);
         $search_month = date("F",$search_date_strtotime);
         $search_year = date("Y",$search_date_strtotime);
        $search_date =  date("j",$search_date_strtotime);
        $day = date("l",$search_date_strtotime);
       $getTask = $this->tasks->get();
       foreach ($getTask as $key => $value) {
           # code...
           $time = $value->created_at;
           $explode = explode(" ",$time);
           $date_data = $explode[0];
           $time_task_creation = strtotime($date_data);
           $day_data = date("j", $time_task_creation);
           $month_data = date("F",$time_task_creation);
           $year_data = date("Y",$time_task_creation);
          if( ( ($search_date==$day_data) && ($search_year==$year_data) && ($search_month==$month_data) ) )
           { 
       
               $users_assigned = json_decode($value->users_assigned);
               
                   $content = $users_assigned;
               
       
       
                         $final_data[] = array("users_assigned"=>$content,"id"=>$value->id,
                   "task_header"=>$value->task_header,"task_content"=>$value->task_content,
               "attached_file"=>$value->attached_file); 
                }
                
            
       
       
           }
           
            $final_returned_data = array("name"=>$staffName,"id"=>$staffId,"data"=> $final_data,
           "day_of_month"=>$search_date,"month"=>$search_month,"year"=>$search_year);
          return $final_returned_data;

     }

}
