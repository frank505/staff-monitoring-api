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

}
