<?php


namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TaskNotifications;
use Validator;
use App\Http\Controllers\SanitizeController;
use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class NotificationsController extends Controller
{
    //
    protected $task_notifier;
  public function __construct()
  {
    $this->middleware("auth:staffs");
    $this->task_notifier = new TaskNotifications;
  }


  public function returnStaffNotifications($token)
  {
    $staff = auth("staffs")->authenticate($token);
    $staffId = $staff->id;
   $total =  $this->task_notifier::where(["staff_id"=>$staffId,"viewed"=>0])->count();
    return response()->json([
        "count"=>$total,
        "success"=>true
    ], 200);
  }


  public function loadPaginatedNotificationsData($token,$pagination)
  {
 
    $staff = auth("staffs")->authenticate($token);
    $staffId = $staff->id;
   $total_data =  $this->task_notifier::where(["staff_id"=>$staffId,"viewed"=>0])->paginate($pagination);
    return response()->json([
        "data"=>$total_data,
        "success"=>true
    ], 200);

  }

  public function dismissAllNotifications(Request $request)
  {
    $validator = Validator::make($request->only("token"), 
    ['token'=>'required'
    ]);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
  $token = $request->token;
    $staff = auth("staffs")->authenticate($token);
    $staffId = $staff->id;
   $updated =  $this->task_notifier::where(["staff_id"=>$staffId,"viewed"=>0])->update([
        "viewed"=>1
    ]);
    if($updated)
    {
        return response()->json([
            "data"=>"notification updated successfully",
            "success"=>true
        ], 200);
    }

  }

  public function dismissTaskNotifications(Request $request)
  {
    $validator = Validator::make($request->only("token"), 
    ['token'=>'required'
    ]);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

      $token = $request->token;
    $staff = auth("staffs")->authenticate($token);
    $staffId = $staff->id;
   $updated =  $this->task_notifier::where(["staff_id"=>$staffId,"viewed"=>0])->where("task_id","!=",0)->update([
        "viewed"=>1
    ]);
    if($updated)
    {
        return response()->json([
            "data"=>"notification updated successfully",
            "success"=>true
        ], 200);
    }

  }

}
