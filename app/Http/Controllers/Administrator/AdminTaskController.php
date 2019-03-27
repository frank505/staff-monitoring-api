<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Routing\UrlGenerator;
use Validator;
use App\Http\Controllers\SanitizeController;
use App\tasks;
use App\Http\Resources\SingleTaskGeterCollection;
use Carbon\Carbon;
use App\User;
use App\FinancialDiscipline;
class AdminTaskController extends Controller
{
    //
    protected $task;
    protected $user;
    protected $financial_discipline;
    public function __construct(UrlGenerator $url)
    {
        $this->middleware("auth:admins");
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
        $this->task = new tasks();
        $this->user = new User();
        $this->financial_discipline = new FinancialDiscipline;
        
    }


    public function CreateTask(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
        'header'=>'required|string',
        'task'=> 'required|string',
        'selected_users'=>'required|string'
        ]
    );

    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

      
      $task_attached_file = $request->file("task_attached_file");
      if($task_attached_file==NULL){
          $file_name = NULL;
      }else{
          $file_extension = $task_attached_file->getClientOriginalExtension();
          //return $file_extension;
          $file_name = uniqid()."_".time().date("Ymd")."_TASK.".$file_extension; //change file name
          $task_dir = "attached_tasks/"; //directory for the image to be uploaded
          $task_attached_file->move($task_dir, $file_name); //more like the move_uploaded_file in php except that more modifications
      }

    $this->task::create([
        "task_header" =>$request->header,
        "users_assigned"=>$request->selected_users,
        "task_content"=>$request->task,
        "attached_file"=>$file_name

    ]);

    return response()->json([
        'success' => true,
        'message' => "new task created successfully"
    ], 200);
  //    var_dump($task_attached_file);
  //    return;
     
    }


//this is to load paginated task
public function loadPaginatedTasks(Request $request,$pagination)
{
    $tasks =  $this->task::orderBy("id","DESC")->paginate($pagination);
    return response()->json([
      "success"=>true,
      "task"=>$tasks,
      "file_directory"=>$this->base_url."/attached_tasks"."/",
  ],200);

}

//this function is to search for users
public function SearchTask($searchval,$pagination)
{
  
  if($searchval=="" ||$searchval==null){

  }else{
    $search = $this->task::where("task_header","LIKE","%$searchval%")->orWhere("users_assigned","LIKE","%$searchval%")->
    orderBy("id","DESC")->paginate($pagination);
    return response()->json([
      "success"=>true,
      "task"=>$search,
      "file_directory"=>$this->base_url."/user_images"."/",
    ],200);
  }
   
}


public function loadSingleTask($id)
{
 // $get_task = $this->where(["id"=>$id])->get();
   $task= $this->task->find($id);
//$task = SingleTaskGeterCollection::collection($get_task);
      return response()->json([
       "success"=>true,
       "task"=>$task,
       "file_directory"=>$this->base_url."/attached_tasks"."/",
      ],200);
}

//    /admin/dissaprove-task/
public function dissaproveTask(Request $request){
  $validator = Validator::make($request->only("id"), 
  ['id'=>'required|integer'
  ]);
  if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
    }

 $id = $request->id;
  $task = $this->task->find($request->id);
  if (!$task) {
    return response()->json([
        'success' => false,
        'message' => 'Sorry, this staff doesnt exist in the database '
    ], 400);
}

$checkTaskDissaproved = $this->task->where(["approval_status"=>1,"id"=>$request->id])->count();
if($checkTaskDissaproved==1){
  return response()->json([
    'success' => false,
    'message' => 'this task has already been disapproved a task cannot be disapprove twice'
], 400);
}
//$created_at = $task->created_at;
//check if current time is greater than 24 hours if true then contnue else stop
$check_task_data = $this->task->where("id",$id)->where('created_at', '<', Carbon::now()->subMinutes(1440)->toDateTimeString())->count();
if($check_task_data==0){
  return response()->json([
    'success' => false,
    'message' => 'approvals of task or dissapproval can only take place after 24 hous of the task being created '
], 400);
}
  $task->approval_status = 1;
  $task->save();



  $ConvertToArray =  json_decode($task->users_assigned,TRUE);
  foreach ($ConvertToArray as $key => $value) {
    # code...
  $user_id = $value["id"];
  $user_name = $value["name"];
  $getUser = $this->user->find($user_id);
  $getUserSalary  = $getUser->earning;
  $numberOfDaysForTheMonth = date("t"); //returns the number of days in the current month
  $day_of_the_month = date("j")."".date("S");//should look like this 23rd the date("S") is for the rd  appended;
  $day = date("l");
  $Month = date("F");
  $Year = date("Y");
  $amountToBeDeducted =  floor($getUserSalary / $numberOfDaysForTheMonth);
  $currentBalance = $getUserSalary - $amountToBeDeducted;
  $this->financial_discipline::create([
    "task_id"=>$request->id,
    "user_id"=>$user_id,
    "name"=>$user_name,
    "salary"=>$getUserSalary,
    "fine"=>$amountToBeDeducted,
    "remaining_balance"=>$currentBalance,
    "day_of_the_month"=>$day_of_the_month,
    "day"=>$day,
    "month"=>$Month,
    "year"=>$Year,
    "staff_punishement_type"=>"incompetence",
    "admin_complaints"=>NULL,
  ]);
 // return "on the ".date("j")." this is your balance".$currentBalance;

  }

  return response()->json([
    'success' => true,
    'message' => 'this task has been dissapproved and staffs salary have been deducted as you requested',
    'staff_punishement_type'=>"incompetence"
], 200);
}

///admin/approve-task/
public function approveTask(Request $request)
{
  $validator = Validator::make($request->only("id"), 
  ['id'=>'required|integer'
  ]);
  if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
    }

 $id = $request->id;
  $task = $this->task->find($request->id);
  if (!$task) {
    return response()->json([
        'success' => false,
        'message' => 'Sorry, this staff doesnt exist in the database '
    ], 400);
}

$checkTaskApproved = $this->task->where(["approval_status"=>0,"id"=>$request->id])->count();
if($checkTaskApproved==1){
  return response()->json([
    'success' => false,
    'message' => 'this task has already been approved a task cannot be approved twice'
], 400);
}
//$created_at = $task->created_at;
//check if current time is greater than 24 hours if true then contnue else stop
$check_task_data = $this->task->where("id",$id)->where('created_at', '<', Carbon::now()->subMinutes(1440)->toDateTimeString())->count();
if($check_task_data==0){
  return response()->json([
    'success' => false,
    'message' => 'approvals of task or dissapproval can only take place after 24 hous of the task being created '
], 400);

}
  $task->approval_status = 0;
  $task->save();



  $ConvertToArray =  json_decode($task->users_assigned,TRUE);
  foreach ($ConvertToArray as $key => $value) {
    # code...
  $user_id = $value["id"];
  $user_name = $value["name"];
  $dataToDelete = $this->financial_discipline::where(["user_id"=>$user_id,"task_id"=>$request->id,"name"=>$user_name])->get();
   foreach ($dataToDelete as $key => $value) {
    $financial_id_to_delete  = $this->financial_discipline::find($value->id);
    $financial_id_to_delete->delete();    
   }
  
  }

  return response()->json([
    'success' => true,
    'message' => 'this task has been approved and staffs salary have been returned back to previous conditions before task approval',
    'staff_punishement_type'=>"incompetence"
], 200);
}


public function layComplaints(Request $request)
{
  $validator = Validator::make($request->only("id","complaints"), 
  ['id'=>'required|integer',
  'complaints'=>'required|string'
  ]);
  if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
    }
 
//  $checkIdExist = $this->financial_discipline::where("task_id",$request->id)->count();
//  if($checkIdExist == 0){
//   return response()->json([
//     "success"=>false,
//     "message"=>"sorry this task have not been  disapproved before",
//     "id"=>$request->id
//    ],400);    
//  } 

 $totalTasksAvailable = $this->financial_discipline::where(["task_id"=>$request->id])->get();
 foreach ($totalTasksAvailable as $key => $value) {
   $complaints_content = $value->admin_complaints;
   if($complaints_content!==NULL){
    return response()->json([
      "success"=>false,
      "complaints_exist"=>"complaints already exist",
     ],200);    
   }
   $complaints_id = $value->id;
   $getContent = $this->financial_discipline::find($complaints_id);
   $getContent->admin_complaints = $request->complaints;
   $getContent->save(); 
  }



 return response()->json([
  "success"=>true,
  "message"=>"complaint message successfully sent",
 ],200); 


}




public function UpdateComplaints(Request $request)
{
  $validator = Validator::make($request->only("id","complaints"), 
  ['id'=>'required|integer',
  'complaints'=>'required|string'
  ]);
  if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
    }
    $totalTasksAvailable = $this->financial_discipline::where(["task_id"=>$request->id])->get();
    foreach ($totalTasksAvailable as $key => $value) {
      $complaints_id = $value->id;
      $getContent = $this->financial_discipline::find($complaints_id);
      $getContent->admin_complaints = $request->complaints;
      $getContent->save(); 
     }
   

    return response()->json([
     "success"=>true,
     "message"=>"complaint message updated successfully sent",
    ],200); 
   
 
}


public function deleteTask(Request $request)
{
  $validator = Validator::make($request->only("id"), 
  ['id'=>'required|integer']);
  if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
    }
 
  $id = $request->id;

  $task = $this->task->find($id);
 
  if (!$task) {
      return response()->json([
          'success' => false,
          'message' => 'Sorry, this staff doesnt exist in the database '
      ], 400);
  }

  $file = $task->attached_file;
  if($file==NULL){
    
  }else{
    unlink(public_path('attached_tasks/'.$file));
  }
  
  if ($task->delete()) {
    return response()->json([
        'success' => true,
        'message'=>'task deleted succesfully',
      
    ]);
 
}

}


public function updateTask(Request $request,$id)
{

$validator = Validator::make($request->all(),
        [
        'header'=>'required|string',
        'task'=> 'required|string',
        ]
    );

    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
  $task = $this->task->find($id);
 
  if (!$task) {
      return response()->json([
          'success' => false,
          'message' => 'Sorry, this staff doesnt exist in the database '
      ], 400);
  }


  $prev_file = $task->attached_file;
  if($prev_file!==NULL){
    unlink(public_path('attached_tasks/'.$prev_file)); 
  }

  $task_attached_file = $request->file("task_attached_file");
if($task_attached_file==NULL){
    $file_name = NULL;
}else{
      
    $file_extension = $task_attached_file->getClientOriginalExtension();
    //return $file_extension;
    $file_name = uniqid()."_".time().date("Ymd")."_TASK.".$file_extension; //change file name
    $task_dir = "attached_tasks/"; //directory for the image to be uploaded
    $task_attached_file->move($task_dir, $file_name); //more like the move_uploaded_file in php except that more modifications
}

$this->task::where(["id"=>$id])->update([
  "task_header" =>$request->header,
  "task_content"=>$request->task,
  "attached_file"=>$file_name

]);


return response()->json([
  'success' => true,
  'message' => "this task has been updated successfully"
], 200);

}


}

