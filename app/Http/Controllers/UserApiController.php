<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
Use App\User;  //User Model
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Http\Resources\UserResource as UserResource;
use Hash;
class UserApiController extends Controller
{
  public $successStatus = 200;
  public $successCode = "0";


   public function __construct()
   {
       $this->middleware('jwt', ['except' => ['create']]);
   }

    public function index()
     {
       //Get All User
       $user = User::paginate(15);
        if($user){
       return UserResource::collection($user);
        }
       if(!$user){
          return response()->json("User Not Found", 204);
       }
     }

     public function show($id)
     {
       //Get Specific User
       $user = User::findOrFail($id);
       if($user){
         return new UserResource($user);
       }
       if(!$user){
          return response()->json("User Not Found", 204);
       }

     }

     public function create(Request $request)
     {
       //Create New User
       //$user = User::create($request->all());
      if($request->isMethod('post')){

      $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
         ]);

         if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
           }
        else{
                       $user = new User();
                       $user->name =  $request->input('name');
                       $user->email =  $request->input('email');
                       $user->password =  Hash::make($request->input('password'));
                       $user->remember_token = str_random(10);
                       $user->save();
                       if($user->save()) {

                             return response()->json(['userdata'=>$user,
                                                      'status'=>$this->successStatus,
                                                      'code'=>$this->successCode,
                                                      'message'=>"User Inserted Successfully"], $this->successStatus);
                       }
                       else{
                            return response()->json("User Not Inserted", 400);
                       }

           }
         }
         else{
             return response()->json("Method not Allow", 400);
         }

     }


     public function update(Request $request, $id)
     {
       //Update User
       if($request->isMethod('post')){
         $validator = Validator::make($request->all(), [
               'email' => 'string|email|max:255',
               'password' => 'string|min:6',
            ]);

            if ($validator->fails()) {
               return response()->json(['error'=>$validator->errors()], 401);
              }

       $user = User::findOrFail($id);
       $user->id =  $user->id;
       $user->name =  $request->input('name');
       $user->email =  $request->input('email');
       $user->password =  Hash::make($request->input('password'));
       $user->update();
       if($user->update()) {
             return response()->json(['userdata'=>$user,
                                      'status'=>'201',
                                      'code'=>$this->successCode,
                                      'message'=>"User Updated Successfully"], 201);
       }
       else{
            return response()->json("User Not Updated", 400);
       }
     }
     else{
         return response()->json("Method not Allow", 405);
     }
    }


     public function destroy(Request $request,$id)
     {
       if($request->isMethod('delete')){

         $user = User::findOrFail($id);
         if($user->delete()){
       return response()->json(['userdata'=>null,
                                'status'=>'204',
                                'code'=>$this->successCode,
                                'message'=>"User Deleted Successfully"], 204);
         }else{
             return response()->json("User Not Deleted", 400);
         }
     }
     else{
         return response()->json("Method not Allow", 400);
     }
     }




}
