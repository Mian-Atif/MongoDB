<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Client as connection;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Http\Requests\searchuserrequest;
use App\Http\Requests\uploadfilerequest;
use App\Http\Requests\forgetpassrequest;
use App\Service\JwtController;

class UserCrude extends Controller
{
 function searchUser(searchuserrequest $request){
        try{
         $token=$request->bearerToken();
         $auth=$request->validated();
         if(isset($token)){
            $db = (new connection)->social->user;
            $result=$db->findOne(["email"=>$auth['email']]);
            return response()->success(['data'=>$result],200);
         }
         else{
             return response()->error(['token'=>"token expire"],404);
         }
        }
        catch(Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }
 function upLoadFile(uploadfilerequest $request){
     try{
         $token=$request->bearerToken();
         $auth=$request->validated();
         $token=(new JwtController)->decodeToken($token);
         $email=$token->data->email;
         $name=$token->data->name;
         if(isset($token)){
            $db = (new connection)->social->user;
            //
            $result=$auth['file']->store($name);
            $db->updateOne(["email"=>$email],
                ['$set'=>['profile'=>$result
                ]]);
            return response()->success(['result'=>$result,'data'=>"file is successfulyy added"],200);
         }
         else{
         return response()->error(['token'=>"token expire"],401);
         }
        }
        catch(Exception $ex){
            return response()->error($ex->getMessage());
            }

    }
 function updateUser(Request $request){
     try{
            $token=$request->bearerToken();
            $token=(new JwtController)->decodeToken($token);
            $email=$token->data->email;
            $db = (new connection)->social->user;
            // dd($request->name);
            $result=$db->updateMany(["email"=>$email],
            ['$set'=>['name'=>$request->name,'phone_no'=>$request->phone_no]
            //'$set'=>['phone_no'=>$request->phone_no]
            ]);
            if($result){
            return response()->success(['result'=>"data is successfuly updated"],200);
            }
            else{
            return response()->error(['result'=>"data is not updated"],404);
            }
        }
     catch(Exception $ex){
         return response()->error($ex->getMessage(),400);
        }
    }

    function forGetPassword(forgetpassrequest $request){
     try{
             $auth=$request->validated();
             $db = (new connection)->social->user;
             $user =$db->findOne(["email"=>$auth['email']]);
          if($auth['favorite_animal']==$user->favorite_animal){
                $new_password= hash::make($auth['new_password']);
                $db->updateOne(["email"=>$auth['email']],
                ['$set'=>['password'=>$new_password
                ]]);

                return response()->success(['data'=>"new password save successfuly"],200);
            }
            else{
             return response()->error(['data'=>"credentials is invailed"],401);
         }
        }
        catch(Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
     }

}
