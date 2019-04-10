<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\admin;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\UrlGenerator;
use Validator;
use App\Http\Controllers\SanitizeController;

class AuthAdminController extends Controller
{
    //
    protected $base_url;
    public $loginAfterSignUp = true;
   protected $admin;
    public function __construct(UrlGenerator $url){
       $this->middleware("auth:admins",['except'=>['login']]);
       $this->admin = new admin();
       $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
   }

    public function register(Request $request)
    {
    $validator = Validator::make($request->only("name","email","password","number"), 
    ['name' => 'required|string',
    'email' => 'required|email',
    'password' => 'required|string|min:6',
    'number'=>'required|integer'
    ]);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
      
      $check_email = $this->admin->where("email",$request->email)->count();
      if($check_email!=0){
        return response()->json([
            "success"=>false,
            "message_email_taken"=>"this email is already taken",
  
        ],400);
           } 
      
      
        $this->admin->name = $request->name;
        $this->admin->email = $request->email;
        $this->admin->phone_number = $request->number;
        $this->admin->password = Hash::make($request->password);
        $this->admin->save();
 
        // if ($this->loginAfterSignUp) {
        //     return $this->login($request);
        // }
 
        return response()->json([
            'success' => true,
            "message"=>"new admin registration successful"
        ], 200);
    }
  
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->only('password','user_token'),
        [
        'password' => 'required',
        'user_token' => 'required'
                ]
    );
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
     
      $admin = auth("admins")->authenticate($request->user_token);
    $admin->password = $request->password;
       $admin->save();
   
    return response()->json([
        'success' => true,
        'data' => "password changed successfully"
    ], 200);
    }

    public function AddProfilePicture(Request $request)
    {
        $validator = Validator::make($request->only('profilephoto','token'),
        [
        'profilephoto.*' => 'required|image|mimes:jpeg,bmp,png|max:8000',
        'token' => 'required'
                ]
    );

    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

      
          $admin = auth("admins")->authenticate($request->token);


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
            ], 500);
          }



          $rename_image = uniqid()."_".time().date("Ymd")."_IMG.".$image_extension; //change file name
         
          $admin_prev_image = $admin->profilephoto;
            if($admin_prev_image==NULL){

            }else{
                unlink(public_path('images/admin/'.$admin_prev_image));
            }
          

          $admin->profilephoto = $rename_image;
          
          $admin->save();
            $admin_dir = "images/admin"; //directory for the image to be uploaded
            $profilephoto->move($admin_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
            
            
        return response()->json([
            'success' => true,
            'data' => "profile photo updated successfully"
        ], 200);
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
 
        if (!$jwt_token = auth("admins")->attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
       //set token expiration time in minutes
       $token_time_frame = auth("admins")->factory()->setTTL(43200);
      
       
    
    return response()->json([
        'success' => true,
        'token' => $jwt_token,
        'expires_in'=>auth("admins")->factory()->getTTL(),
    ]);
    }
 
    public function logout(Request $request)
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
            auth("admins")->invalidate($request->token);
 
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
   
    public function getAuthadmin(Request $request)
    {
        $validator = Validator::make($request->only('token'), 
        ['token' => 'required']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
        $admin = auth("admins")->authenticate($request->token);
 
        return response()->json([
            'user' => $admin,
            'image_directory'=>$this->base_url."/images/admin",
        ],200);
    }

   
    
    public function saveOrUpdatePushToken(Request $request)
    {
        $validator = Validator::make($request->only('token', 'push_token'), 
        ['token' => 'required',
        'push_token'=> 'required'
        ]);

        //return $request->all();

        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
          $admin = auth("admins")->authenticate($request->token);
          $admin->push_token = $request->push_token;
          $admin->save();
          return response()->json([
            "success"=>true,
            "message"=>"token saved successfully",
           ],200);
    }
    


        
        public function returnResponseWithToken($token)
        {
            
         return response()->json([
             'success'=>true,
              'access_token'=>$token,
              'token_type'=>'Bearer',
              'expires_in'=>auth()->factory()->getTTL() * 60 * 24,
            ]);
        }
//gets all admins in a paginated manner
        public function viewAdmins($pagination)
        {
          $admin =  $this->admin::orderBy("id","DESC")->paginate($pagination);
          return response()->json([
            "success"=>true,
            "user"=>$admin
        ],200);
        }
}
