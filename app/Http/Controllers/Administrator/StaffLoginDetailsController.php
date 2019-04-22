<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StaffLoginNotificationsDetail;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\Http\Controllers\SanitizeController;
use Carbon\Carbon;
use App\FinancialDiscipline;
class StaffLoginDetailsController extends Controller
{
    protected $staff_login_details;
   protected $staffs;
    public function __construct()
    {
        $this->middleware("auth:admins");
        $this->staff_login_details = new StaffLoginNotificationsDetail;
        $this->staffs = new User;
        $this->financial_discipline = new FinancialDiscipline;
    }
    //
   
    public function CurrentlyLoggedInStaffs()
    {
       // $time = date("H:i:s");
       // $day = date("l");
       $data = array();
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






  public function GetUserSearchLoginReport(Request $request,$search)
  {
    if($search=="" || $search==null)
    {
      return response()->json([
        "success"=>false,
        "message"=>"please select a date",
       ],400);   
    }
    $search_data = strtotime($search);
     $current_time = time();
     //check if user is not searching for a non existent year
     if($current_time < $search_data){
      return response()->json([
        "success"=>false,
        "message"=>"this period selected seems to be in the future only past dates and current dates can be selected are accepted",
       ],400);   
     }
    $day = date("l",$search_data);
    $Month =  date("F",$search_data);
    $year = date("Y",$search_data);
    $day_of_month = date("j",$search_data);
   $loggedInUsers = $this->loggedInSearchedStaffs($search);
   $notLoggedInUsers = $this->absentSearchStaffsForTheDay($search);
  $data = array("logged_in"=>$loggedInUsers,"not_logged_in"=>$notLoggedInUsers,"day"=>$day,
  "month"=>$Month,"year"=>$year,"date"=>$day_of_month);
  return response()->json([
     "success"=>true,
     "data"=>$data
    ],200);  


  }





  public function loggedInSearchedStaffs($search)
  {
    $data_search = strtotime($search);
    $data = array();
    $Month =  date("F",$data_search);
    $year = date("Y",$data_search);
    $day_of_month = date("j",$data_search);
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









  public function absentSearchStaffsForTheDay($search)
  {
    $data_search = strtotime($search);
    $Month =  date("F",$data_search);
    $year = date("Y",$data_search);
    $day_of_month = date("j",$data_search);
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














  public function ShowFullLoginDetails(Request $request)
  {
    $validator = Validator::make($request->only('id','date','month','year','day'), 
    ['id' => 'required|integer',
    'date' => 'required|string',
    'month' => 'required|string',
    'year' => 'required|string',
    'day' => 'required|string'
    ]);
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }

$getDetails = $this->staff_login_details::where(["staff_id"=>$request->id,"date"=>$request->date,
"month"=>$request->month,"year"=>$request->year,"day"=>$request->day])->get();
return response()->json([
    'success' => true,
     'data'=>$getDetails
],200);
}















public function DeleteFinancialPunishement(Request $request)
{
  $loginpunishement = "attendance";
  $validator = Validator::make($request->only('date','month','year','day','id','name'), 
  [
  'date' => 'required|string',
  'month' => 'required|string',
  'year' => 'required|string',
  'day' => 'required|string',
  'id'=>'required|integer',
  'name'=>'required|string'
  ]);
if($validator->fails()){
  return response()->json([
   "success"=>false,
   "message"=>$validator->messages()->toArray(),
  ],400);    
}
// "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
// "month","year","staff_punishement_type","admin_complaints"
$count = $this->financial_discipline::where(
  [
    "task_id"=>0,
"user_id"=>$request->id,
"name"=>$request->name,
//"fine"=>$request->amount,
"day_of_the_month"=>$request->date,
"month"=>$request->month,
"year"=>$request->year,
//day_of_the_month
"day"=>$request->day,
"staff_punishement_type"=>$loginpunishement
])->count();

if($count==0)
{
  return response()->json([
    "success"=>false,
    "message"=>"staff was not punished before",
   ],400);   
}

$delete = $this->financial_discipline::where(
  [
    "task_id"=>0,
"user_id"=>$request->id,
"name"=>$request->name,
//"fine"=>$request->amount,
"day_of_the_month"=>$request->date,
"month"=>$request->month,
"year"=>$request->year,
//day_of_the_month
"day"=>$request->day,
"staff_punishement_type"=>$loginpunishement
])->delete();


    return response()->json([
      "success"=>true,
      "message"=>"staff financial discipline for the day successfully reset/deleted"
     ],200);    

}











public function deductMoneyFromStaff(Request $request)
{
    $loginpunishement = "attendance";
    $validator = Validator::make($request->only('complaints','amount','date','month','year','day','id','name'), 
    ['complaints' => 'required|string',
    'amount'=>'required|integer',
    'date' => 'required|string',
    'month' => 'required|string',
    'year' => 'required|string',
    'day' => 'required|string',
    'id'=>'required|integer',
    'name'=>'required|string'
    ]);
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
  // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
  // "month","year","staff_punishement_type","admin_complaints"
  $count = $this->financial_discipline::where(
    [
      "task_id"=>0,
  "user_id"=>$request->id,
  "name"=>$request->name,
  //"fine"=>$request->amount,
  "day_of_the_month"=>$request->date,
  "month"=>$request->month,
  "year"=>$request->year,
  //day_of_the_month
  "day"=>$request->day,
  "staff_punishement_type"=>$loginpunishement
  ])->count();
  if($count!=0){
    return response()->json([
        "success"=>false,
        "data_exist"=>"content exist"
       ],200);    
  }
      
 //get staff salary
  $staff_content = $this->staffs::find($request->id);
  $staff_salary = $staff_content->earning;
 // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
  // "month","year","staff_punishement_type","admin_complaints"
  $remaining_balance = $staff_salary - $request->amount;
      $this->financial_discipline::create([
        "task_id"=>0,
        "user_id"=>$request->id,
        "name"=>$request->name,
        "salary"=>$staff_salary,
        "fine"=>$request->amount,
        "remaining_balance"=>$remaining_balance,
        "day_of_the_month"=>$request->date,
        "month"=>$request->month,
        "year"=>$request->year,
        "day"=>$request->day,
        "admin_complaints"=>$request->complaints,
        "staff_punishement_type"=>$loginpunishement
      ]);
      $task_header = "(attendance punished) on the $request->date/$request->month/$request->year and salary has been deducted";
      $this->insertTaskNotification($request->id,$request->name,$task_header,0);
      

      return response()->json([
        "success"=>true,
        "message"=>"staff salary successfully withdrawn"
       ],200);    

  }






  
  public function insertTaskNotification($user_id,$user_name,$task_header,$task_id)
{
  $this->task_notifications::create([
  "task_id"=>$task_id,
  "task_header"=>$task_header,
  "staff_id"=>$user_id,
  "staff_name"=>$user_name,
  "viewed"=>0
 ]);
}







//update content
  public function UpdateStaffSalaryDeduct(Request $request)
  {
    $loginpunishement = "attendance";
    $validator = Validator::make($request->only('complaints','amount','date','month','year','day','id','name'), 
    ['complaints' => 'required|string',
    'amount'=>'required|integer',
    'date' => 'required|string',
    'month' => 'required|string',
    'year' => 'required|string',
    'day' => 'required|string',
    'id'=>'required|integer',
    'name'=>'required|string'
    ]);
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
    
 //get staff salary
  $staff_content = $this->staffs::find($request->id);
  $staff_salary = $staff_content->earning;
 // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
  // "month","year","staff_punishement_type","admin_complaints"
  $remaining_balance = $staff_salary - $request->amount;
      $this->financial_discipline::where(
     [
      "task_id"=>0,
      "user_id"=>$request->id,
      "name"=>$request->name,
      //"fine"=>$request->amount,
      "day_of_the_month"=>$request->date,
      "month"=>$request->month,
      "year"=>$request->year,
      //day_of_the_month
      "day"=>$request->day,
      "staff_punishement_type"=>$loginpunishement
     ]     
      )->update([
        "task_id"=>0,
        "user_id"=>$request->id,
        "name"=>$request->name,
        "salary"=>$staff_salary,
        "fine"=>$request->amount,
        "remaining_balance"=>$remaining_balance,
        "day_of_the_month"=>$request->date,
        "month"=>$request->month,
        "year"=>$request->year,
        "day"=>$request->day,
        "admin_complaints"=>$request->complaints,
        "staff_punishement_type"=>$loginpunishement
      ]);
      $task_header = "(attendance punishement updated) on the $request->date/$request->month/$request->year and salary has been deducted";
      $this->insertTaskNotification($request->id,$request->name,$task_header,0);
      return response()->json([
        "success"=>true,
        "message"=>"staff salary dedudction successfully updated successfully"
       ],200);    
          
  }








  //deducting staff salary by default for those who didnt come to work
  public function DeductSalaryByDefault(Request $request)
  {
    $loginpunishement = "attendance";
    $validator = Validator::make($request->only('date','month','year','day','id','name','correct_date_time'), 
    [
    'date' => 'required|string',
    'month' => 'required|string',
    'year' => 'required|string',
    'day' => 'required|string',
    'id'=>'required|integer',
    'name'=>'required|string',
    'correct_date_time'=>'required|string'
    ]);

    //return $request->all();
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
//   // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
//   // "month","year","staff_punishement_type","admin_complaints"
  $count = $this->financial_discipline::where(
    [
      "task_id"=>0,
  "user_id"=>$request->id,
  "name"=>$request->name,
  //"fine"=>$request->amount,
  "day_of_the_month"=>$request->date,
  "month"=>$request->month,
  "year"=>$request->year,
  //day_of_the_month
  "day"=>$request->day,
  "staff_punishement_type"=>$loginpunishement
  ])->count();
  if($count!=0){
    return response()->json([
        "success"=>false,
        "data_exist"=>"content exist"
       ],200);    
  }
      
 //get staff salary
  $staff_content = $this->staffs::find($request->id);
  $staff_salary = $staff_content->earning;
  //get number of days avaailable for that moment
  $no_of_days_available = strtotime($request->correct_date_time);
  $no_of_days_available = date("t",$no_of_days_available);
  $staff_amount_to_deduct = floor($staff_salary  /  $no_of_days_available);
 // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
  // "month","year","staff_punishement_type","admin_complaints"
  $remaining_balance = $staff_salary - $staff_amount_to_deduct;
      $this->financial_discipline::create([
        "task_id"=>0,
        "user_id"=>$request->id,
        "name"=>$request->name,
        "salary"=>$staff_salary,
        "fine"=>$staff_amount_to_deduct,
        "remaining_balance"=>$remaining_balance,
        "day_of_the_month"=>$request->date,
        "month"=>$request->month,
        "year"=>$request->year,
        "day"=>$request->day,
       // "admin_complaints"=>$request->complaints,
        "staff_punishement_type"=>$loginpunishement
      ]);

      $task_header = "(attendance punished) on the $request->date/$request->month/$request->year and salary has been deducted";
      $this->insertTaskNotification($request->id,$request->name,$task_header,0);
    

      return response()->json([
        "success"=>true,
        "message"=>"staff salary successfully withdrawns"
       ],200);    

  }







  public function DeductSalaryCustom(Request $request)
  {
    $complaints ="";
    $loginpunishement = "attendance";
    $validator = Validator::make($request->only('date','month','year','day','id','name','correct_date_time','complaints','amount'), 
    [
    'date' => 'required|string',
    'month' => 'required|string',
    'year' => 'required|string',
    'day' => 'required|string',
    'id'=>'required|integer',
    'name'=>'required|string',
    'correct_date_time'=>'required|string',
    'complaints'=>'required|string',
    'amount'=>'required|integer'
    ]);

    //return $request->all();
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
//   // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
//   // "month","year","staff_punishement_type","admin_complaints"

//get staff salary
$staff_content = $this->staffs::find($request->id);
$staff_salary = $staff_content->earning;
//get number of days avaailable for that moment


// "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
// "month","year","staff_punishement_type","admin_complaints"
$remaining_balance = $staff_salary - $request->amount;


$count = $this->financial_discipline::where(
  [
    "task_id"=>0,
"user_id"=>$request->id,
"name"=>$request->name,
//"fine"=>$request->amount,
"day_of_the_month"=>$request->date,
"month"=>$request->month,
"year"=>$request->year,
//day_of_the_month
"day"=>$request->day,
"staff_punishement_type"=>$loginpunishement
])->count();

//if content doesnt exist at all then we do an insert 
if($count !== 0)
{
  return response()->json([
    "success"=>false,
    "data_exist"=>"content exist"
   ],200);
}

  //we insert  
  $this->financial_discipline::create([
    "task_id"=>0,
    "user_id"=>$request->id,
    "name"=>$request->name,
    "salary"=>$staff_salary,
    "fine"=>$request->amount,
    "remaining_balance"=>$remaining_balance,
    "day_of_the_month"=>$request->date,
    "month"=>$request->month,
    "year"=>$request->year,
    "day"=>$request->day,
    "admin_complaints"=>$request->complaints,
    "staff_punishement_type"=>$loginpunishement
  ]);

  $task_header = "(attendance punished) on the $request->date/$request->month/$request->year and salary has been deducted";
  $this->insertTaskNotification($request->id,$request->name,$task_header,0);

  return response()->json([
    "success"=>true,
    "message"=>"staff salary successfully withdrawns"
   ],200);    

  }


  public function UpdateDeductSalaryCustom(Request $request)
  {
    $complaints ="";
    $loginpunishement = "attendance";
    $validator = Validator::make($request->only('date','month','year','day','id','name','correct_date_time','complaints','amount'), 
    [
    'date' => 'required|string',
    'month' => 'required|string',
    'year' => 'required|string',
    'day' => 'required|string',
    'id'=>'required|integer',
    'name'=>'required|string',
    'correct_date_time'=>'required|string',
    'complaints'=>'required|string',
    'amount'=>'required|integer'
    ]);

    //return $request->all();
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
//   // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
//   // "month","year","staff_punishement_type","admin_complaints"

//get staff salary
$staff_content = $this->staffs::find($request->id);
$staff_salary = $staff_content->earning;
//get number of days avaailable for that moment


// "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
// "month","year","staff_punishement_type","admin_complaints"
$remaining_balance = $staff_salary - $request->amount;
//we insert  
  $this->financial_discipline::where(
    [
      "task_id"=>0,
  "user_id"=>$request->id,
  "name"=>$request->name,
  //"fine"=>$request->amount,
  "day_of_the_month"=>$request->date,
  "month"=>$request->month,
  "year"=>$request->year,
  //day_of_the_month
  "day"=>$request->day,
  "staff_punishement_type"=>$loginpunishement
  ])->update([
    "task_id"=>0,
    "user_id"=>$request->id,
    "name"=>$request->name,
    "salary"=>$staff_salary,
    "fine"=>$request->amount,
    "remaining_balance"=>$remaining_balance,
    "day_of_the_month"=>$request->date,
    "month"=>$request->month,
    "year"=>$request->year,
    "day"=>$request->day,
    "admin_complaints"=>$request->complaints,
    "staff_punishement_type"=>$loginpunishement
  ]);

  $task_header = "(attendance punishement updated) on the $request->date/$request->month/$request->year and salary has been deducted";
  $this->insertTaskNotification($request->id,$request->name,$task_header,0);

  return response()->json([
    "success"=>true,
    "message"=>"staff salary withdrawal successfully updated"
   ],200);    

  }





public function UpdateDeductSalaryDefault(Request $request)
{
  $loginpunishement = "attendance";
    $validator = Validator::make($request->only('date','month','year','day','id','name','correct_date_time'), 
    [
    'date' => 'required|string',
    'month' => 'required|string',
    'year' => 'required|string',
    'day' => 'required|string',
    'id'=>'required|integer',
    'name'=>'required|string',
    'correct_date_time'=>'required|string'
    ]);

    //return $request->all();
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
//   // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
//   // "month","year","staff_punishement_type","admin_complaints"     
 //get staff salary
  $staff_content = $this->staffs::find($request->id);
  $staff_salary = $staff_content->earning;
  //get number of days avaailable for that moment
  $no_of_days_available = strtotime($request->correct_date_time);
  $no_of_days_available = date("t",$no_of_days_available);
  $staff_amount_to_deduct = floor($staff_salary  /  $no_of_days_available);
 // "task_id","user_id","name","salary","fine","remaining_balance","day_of_the_month","day",
  // "month","year","staff_punishement_type","admin_complaints"
  $remaining_balance = $staff_salary - $staff_amount_to_deduct;
      $this->financial_discipline::where(
        [
          "task_id"=>0,
  "user_id"=>$request->id,
  "name"=>$request->name,
  //"fine"=>$request->amount,
  "day_of_the_month"=>$request->date,
  "month"=>$request->month,
  "year"=>$request->year,
  //day_of_the_month
  "day"=>$request->day,
  "staff_punishement_type"=>$loginpunishement
        ]
      )->update([
        "task_id"=>0,
        "user_id"=>$request->id,
        "name"=>$request->name,
        "salary"=>$staff_salary,
        "fine"=>$staff_amount_to_deduct,
        "remaining_balance"=>$remaining_balance,
        "day_of_the_month"=>$request->date,
        "month"=>$request->month,
        "year"=>$request->year,
        "day"=>$request->day,
        "admin_complaints"=>NULL,
        "staff_punishement_type"=>$loginpunishement
      ]);

      $task_header = "(attendance punishement updated) on the $request->date/$request->month/$request->year and salary has been deducted";
  $this->insertTaskNotification($request->id,$request->name,$task_header,0);

      return response()->json([
        "success"=>true,
        "message"=>"staff salary withdrawal successfully updated"
       ],200);    
}





}