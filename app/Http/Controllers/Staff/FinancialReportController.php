<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FinancialDiscipline;
use Validator;
use App\Http\Controllers\SanitizeController;
use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FinancialReportController extends Controller
{
    //
    protected $financial_discipline;
    public function __construct()
    {
        $this->middleware("auth:staffs");
        $this->financial_discipline = new FinancialDiscipline;
    }

    public function MonthlyReport($token)
    {
        //get current staff name and staff id
        $total_fine = 0;
        $staff = auth("staffs")->authenticate($token);
        $id = $staff->id;
        $UserCurrentSalary = $staff->earning;
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
// /available-years/
    //this function loads the avaialble years
    public function loadYearAvailableForUser($token)
    {
        $staff = auth("staffs")->authenticate($token);
        $id = $staff->id;
        return   $this->financial_discipline->where(["user_id"=>$id])->distinct()->get(["year","name"]);

    }
//this function gets the user financial report
    public function GetUserFinancialReport($token,$month,$year)
    {
        $content = array();
        $staff = auth("staffs")->authenticate($token);
        $id = $staff->id;
  $array_data = array();
   $GetData = $this->financial_discipline->
   where(["user_id"=>$id,"month"=>$month,"year"=>$year])->distinct()->get(["task_id"]);
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
}
