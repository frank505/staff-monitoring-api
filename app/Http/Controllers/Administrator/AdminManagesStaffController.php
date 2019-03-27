<?php
//note hear that staffs actually means users
namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\UrlGenerator;
use Validator;
use App\Http\Controllers\SanitizeController;
use App\Http\Controllers\Controller;
use App\ManageUser;

class AdminManagesStaffController extends Controller
{
    //
    protected $users;

  public function __construct(UrlGenerator $url)
  {
      $this->middleware("auth:admins");
    $this->users = new ManageUser;
    $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
  }
    public function register(Request $request)
    {
    $validator = Validator::make($request->only("name","email","password","number","salary"), 
    ['name' => 'required|string',
    'email' => 'required|email',
    'password' => 'required|string|min:6',
    'number'=>'required',
    'salary'=>'required|integer'
    ]);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
      
      $check_email = $this->users->where("email",$request->email)->count();
      if($check_email!=0){
        return response()->json([
            "success"=>false,
            "message_email_taken"=>"this email is already taken",
  
        ],400);
           } 
      
      
        $this->users->name = $request->name;
        $this->users->email = $request->email;
        $this->users->phone_number = $request->number;
        $this->users->password = Hash::make($request->password);
        $this->users->earning = $request->salary;
        $this->users->profilephoto = "default-avatar.png";
        $this->users->save();
 
        // if ($this->loginAfterSignUp) {
        //     return $this->login($request);
        // }
 
        return response()->json([
            'success' => true,
            "message"=>"new users registration successful"
        ], 200);
    }

    //this is to load users profile 
    public function LoadUsers(Request $request,$pagination)
    {
        $users =  $this->users::orderBy("id","DESC")->paginate($pagination);
          return response()->json([
            "success"=>true,
            "user"=>$users,
            "file_directory"=>$this->base_url."/user_images"."/",
        ],200);
    }
//fetch isngle user/staff
    public function LoadUser(Request $request,$id)
    {
      $user= $this->users->find($id);
      return response()->json([
       "success"=>true,
       "user"=>$user,
       "file_directory"=>$this->base_url."/user_images"."/",
      ],200);
    }


    //this function is to search for users
    public function SearchUsers($searchval,$pagination)
    {
      if($searchval=="" ||$searchval==null){

      }else{
        $search = $this->users::where("email","LIKE","%$searchval%")->orWhere("name","LIKE","%$searchval%")->
        orWhere("phone_number","LIKE","%$searchval%")->orderBy("id","DESC")->paginate($pagination);
        return response()->json([
          "success"=>true,
          "user"=>$search,
          "file_directory"=>$this->base_url."/user_images"."/",
        ],200);
      }
       
    }
 //tjis function is to delete a staff/user
 public function DeleteUser($id)
 {
  $users = $this->users->find($id);
 
  if (!$users) {
      return response()->json([
          'success' => false,
          'message' => 'Sorry, this staff doesnt exist in the database '
      ], 400);
  }

  $users_prev_image = $users->image;
  if ($users->delete()) {
      return response()->json([
          'success' => true,
          'message'=>'staff deleted succesfully',
        
      ]);
  } else {
      return response()->json([
          'success' => false,
          'message' => 'staff was not deleted there seems to be a problem logout and ogin again to fix this'
      ], 500);
  }
 }
}
