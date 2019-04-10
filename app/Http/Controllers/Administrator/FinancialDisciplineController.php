<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Routing\UrlGenerator;
use Validator;
use App\Http\Controllers\SanitizeController;
use Carbon\Carbon;
use App\User;
use App\FinancialDiscipline;

class FinancialDisciplineController extends Controller
{
    //
    protected $user;
    protected $financial_discipline;
    public function __construct()
    {
        $this->middleware("auth:admins");
        $this->user = new User();
        $this->financial_discipline = new FinancialDiscipline;
    }


    public function loadFinanceForTheMonth(Request $request, $id)
    {
        $total_fine = 0;
     $user =  $this->user->find($id);
     if(!$user){
        return response()->json([
            'success' => false,
            'message' => 'Sorry, this staff doesnt exist in the database '
        ], 400);
     }
     $UserCurrentSalary = $user->earning;

  $CurrentMonth = date("F");
  $CurrentYear = date("Y");
  $UserFinancialDisciplineReport =  $this->financial_discipline::where(["user_id"=>$id,"month"=>$CurrentMonth,"year"=>$CurrentYear])->get();
      foreach ($UserFinancialDisciplineReport as $key => $value) {
          # code...
          $fine = $value->fine;
          $total_fine += $fine; 
      }

      $salaryRemaining = $UserCurrentSalary - $total_fine;
      $salaryRemainderPercentage =  ($salaryRemaining/$UserCurrentSalary) * 100;
      $finePercentage = 100 -  $salaryRemainderPercentage;
      $data = array("salary"=>$UserCurrentSalary,"fine"=>$total_fine,"salary_remainder"=>$salaryRemaining,
      "salary_percentage_remainder"=>$salaryRemainderPercentage,"fine_percentage"=>$finePercentage);
      return response()->json([
       "success"=>true,
       "data"=>$data
      ],200);

    }
//this function loads the avaialble years
    public function loadYearAvailableForUser(Request $request,$id)
    {
        $user =  $this->user->find($id);
        if(!$user){
           return response()->json([
               'success' => false,
               'message' => 'Sorry, this staff doesnt exist in the database '
           ], 400);
        }
        return   $this->financial_discipline->where(["user_id"=>$id])->distinct()->get(["year","name"]);

    }
//this function gets the user financial report
    public function GetUserFinancialReport(Request $request)
    {
        $content = array();
        $validator = Validator::make($request->all(),
        [
        'month'=>'required|string',
        'year'=> 'required|string',
        'id'=>'required|integer'
        ]
    );

    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
       
     // return $request->all();
  $array_data = array();
   $GetData = $this->financial_discipline->
   where(["user_id"=>$request->id,"month"=>$request->month,"year"=>$request->year])->distinct()->get(["task_id"]);
   foreach ($GetData as $key => $value) {
      $task_id = $value->task_id;
      $getTaskId = $this->financial_discipline->where(["task_id"=>$task_id])->get();
      foreach ($getTaskId as $key => $contents) {
          # code...
       $content[$value->task_id] = $getTaskId; 
         
      }
   }

   

   return response()->json([
    "success"=>true,
    "message"=>$content,
   ],200);

    }

 //end of this class  
}
