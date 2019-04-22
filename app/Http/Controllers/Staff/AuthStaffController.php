<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\UrlGenerator;
use Validator;
use App\Http\Controllers\SanitizeController;
use Carbon\Carbon;
use App\StaffLoginNotificationsDetail;
use App\Http\Controllers\SendPushNotificationsController;


class AuthStaffController extends Controller
{
    //
     //
     protected $staff_login_detail;
     protected $base_url;
     public $loginAfterSignUp = true;
    protected $staff;
    protected $push;
     public function __construct(UrlGenerator $url){
        $this->middleware("auth:staffs",['except'=>['login']]);
        $this->staff = new User();
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
        $this->staff_login_detail = new StaffLoginNotificationsDetail;
        $this->push = new SendPushNotificationsController($url);
    }
 

   public function login(Request $request)
   {
    $validator = Validator::make($request->only('email', 'password'), 
    ['email' => 'required|email',
    'password' => 'required|string|min:6']);
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
     $input = $request->only("email","password");
    $jwt_token = null;

    if (!$jwt_token = auth("staffs")->attempt($input)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid Email or Password',
        ], 401);
    }
   //set token expiration time in minutes
   $token_time_frame = auth("staffs")->factory()->setTTL(43200);

   //check if its his first time of login
   $checkIfFirstLogin = $this->staff->where(["email"=>$request->email])->get();
   foreach ($checkIfFirstLogin as $key => $value) {
       $first_login = $value->first_login;
       $staff_id = $value->id;
       $staff_name = $value->name;
   }
    if($first_login==null)
    {
       $staffIdToUse =  $this->staff->find($staff_id);
       $staffIdToUse->first_login = 1;
       $staffIdToUse->last_Login = Carbon::now(); 
       $staffIdToUse->save();
    $this->updateNotificationsTable($staff_id,$staff_name);
   $this->push->AdminGetUserLoginPush(); //send push notification to admin that user just logged in
    $this->NotificationSentIndicator();
       return response()->json([
        'success' => true,
        'token' => $jwt_token,
        'first_login'=>true,
        'expires_in'=>auth("staffs")->factory()->getTTL(),
    ]);
    }

    $staffIdToUse =  $this->staff->find($staff_id);
    $staffIdToUse->last_Login = Carbon::now(); 
    $staffIdToUse->save();

 $this->updateNotificationsTable($staff_id,$staff_name);///
  $this->push->AdminGetUserLoginPush(); //send push notification to admin that user just logged in
 $this->NotificationSentIndicator();

return response()->json([
    'success' => true,
    'token' => $jwt_token,
    'first_login'=>false,
    'expires_in'=>auth("staffs")->factory()->getTTL(),
]);
   }


public function updateNotificationsTable($staff_id,$staff_name)
{
    $date = Carbon::now();
       $time = date("H:i:s");
       $day = date("l");
       $Month =  date("F");
       $year = date("Y");
       $day_of_month = date("j");
       $staffs_recieve_push = 0; 
     $this->staff_login_detail->create([
         "staff_id"=>$staff_id,
         "staff_name"=>$staff_name,
         "time"=>$time,
         "day"=>$day,
         "month"=>$Month,
         "year"=>$year,
         "date"=>$day_of_month,
         "admin_recieve_push"=>$staffs_recieve_push
     ]);
}

//this function indicates that notification is already sent
public function NotificationSentIndicator()
{
return  $this->staff_login_detail->where(["admin_recieve_push"=>0])->update(["admin_recieve_push"=>1]);
}


public function sendResetPasswordLink(Request $request)
{
     // return $request->all();
     $validator = Validator::make($request->only('email'), 
     ['email' => 'required']);
     if($validator->fails()){
         return response()->json([
          "success"=>false,
          "message"=>$validator->messages()->toArray(),
         ],400);    
 }

 $check_email_exist = $this->staff::where(["email"=>$request->email])->count();
 if($check_email_exist==0){
     return response()->json([
         "success"=>false,
         "message"=>"user with this email does not exist"
     ],400);
 }else{
     $this->sendEmailData($request->email);
     return response()->json([
         "success"=>true,
         "message"=>"reset password link has been sent to your email please click on the link to reset your password",
        ],200);    

 }
}


public function sendEmail($email)
{
    $token = $this->createToken($user_email);
    return Mail::to($user_email)->send(new StaffPasswordReset($token));  
}


public function createToken($email)
{
    $token = str_random(60);
      $this->saveToken($token,$user_email);
      return $token;
}

public function saveToken($token,$email)
{
    DB::table('password_resets')->insert(
        [
            "email"=>$email,"token"=>$token,"created_at"=>Carbon::now()
            ]
        );
}

 
public function getAuthStaff($token)
{
    $staff = auth("staffs")->authenticate($token);

    return response()->json([
        'staffs' => $staff,
        'image_directory'=>$this->base_url."/user_images"."/",
    ],200);
}



public function logout()
{
    $validator = Validator::make($request->only('token'), 
    ['token' => 'required']);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

    try {
        auth("staffs")->invalidate($request->token);

        return response()->json([
            'success' => true,
            'message' => 'admin logged out successfully'
        ]);
    } catch (JWTException $exception) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, the admin cannot be logged out'
        ], 500);

    }

}



public function resetPassword(Request $request)
{
  
    $validator = Validator::make($request->all(), 
    ['password' => 'required','token'=>'required']);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
}

$user = auth("staffs")->authenticate($request->token);

  $hash_password = Hash::make($request->password);
 $user->password = $hash_password;
   if($user->save()){
    return response()->json([
        'success' => true,
        "message"=>"password changed sucessfully"
    ],200);
   }
}

public function AddProfilePicture(Request $request,$token)
{
$validator = Validator::make($request->only('profilephoto'),
        [
        'profilephoto.*' => 'required|image|mimes:jpeg,bmp,png|max:8000'
                ]
    );

    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

      
          $staffs = auth("staffs")->authenticate($token);


            $profilephoto = $request->file("profilephoto");
            if($profilephoto==NULL){
                return response()->json([
                    'success' => false,
                    'message' => 'please select an image'
                ], 500);    
            }
        //    var_dump($profilephoto);
        //    return;
        $image_extension = $profilephoto->getClientOriginalExtension();
        if($image_extension==NULL){
            return response()->json([
                'required' => 'please upload an image'
            ], 500);
          }
       
            
         if(SanitizeController::CheckFileExtensions($image_extension,array("png","jpg","jpeg","PNG","JPG","JPEG"))==FALSE){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this is not an image please ensure your images are png or jpeg files'
            ], 400);
          }



          $rename_image = uniqid()."_".time().date("Ymd")."_IMG.".$image_extension; //change file name
         
          $staffs_prev_image = $staffs->profilephoto;
            if($staffs_prev_image==NULL){

            }else if($staffs_prev_image=="default-avatar.png")
            {

            }
            else{
                unlink(public_path('images/user_images/'.$staffs_prev_image));
            }
          

          $staffs->profilephoto = $rename_image;
          
          $staffs->save();
            $staffs_dir = "images/user_images"; //directory for the image to be uploaded
            $profilephoto->move($staffs_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
            
            
        return response()->json([
            'success' => true,
            'data' => "profile photo updated successfully"
        ], 200);

    }

}
