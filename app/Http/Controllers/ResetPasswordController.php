<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    //
    public function __construct()
    {

    }

    public function showResetPasswordView()
    {
        return view("staff.reset-password.resetpassword");
    }

    public function ResetPasswordAction(Request $request)
    {
        $validator = $this->validate($request, [
            'email' => 'required|email',
        'password' => 'required|string|min:6',
        'password_confirmation' => 'required|string|min:6',
        'hidden_token'=>'required'
        ]);

        $token_value = $request->hidden_token;
    //   $prevToken = DB::table("password_resets")->where("email",$user_email)->first();
    //   if($prevToken){
    //       return $prevToken;
    //   }
       if($request->password != $request->password_confirmation)
       {
        return redirect("/reset-password?token=".$token_value)->with("error_password_same","password and password confirmation must be the same");
       }else{
           
                    $token =  DB::table("password_resets")->where(["token"=>$token_value,"email"=>$request->email])->count();
            if($token > 0){
                DB::table("users")->where(["email"=>$request->email])->update([
                    "password"=>Hash::make($request->password)
                ]);
                return redirect("/reset-password?token=".$token_value)->with("success","password reset successfully");
            }else{
                return redirect("/reset-password?token=".$token_value)->with("error_password_and_token_dont_match","you are not authorized to perform this action");
            }

       }
       
    }








    
}
