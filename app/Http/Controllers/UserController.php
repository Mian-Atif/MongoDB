<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use MongoDB\Client as connection;
use App\Http\Requests\signuprequest;
use App\Http\Requests\signinrequest;
use App\Service\JwtController;
//use App\Service\databaseCon;
class UserController extends Controller
{
    function signUp(signuprequest $request){
        try{
            $auth=$request->validated();
            $db = (new connection)->social->user;
            $date=date('Y-m-d h:i:s');
            $var=$db->insertOne([
            'name'             =>  $auth['name'],
            'email'            => $auth['email'],
            'password'         => hash::make($auth['password']),
            'phone_no'         => $auth['phone_no'],
            'profile'          => $request->profile,
            'favorite_animal'  => $auth['favorite_animal'],
            'email_verified_at'=>'null',
            'remember_token'   =>null,
            'updated_at'       =>$date,
            'created_at'       =>$date
         ]);

         if($var){
                // data creation for email
                $details['link']=url('api/emailConfirmation/'.$request->email);
                $details['user_name']= $request->name;
                $details['email']=$request->email;

                //send verification mail
                Mail::to($request->email)->send(new \App\Mail\EmailVerification($details));
                return response()->success(["result"=>"user is successfully signup"],200);
            }
            else{
                return response()->error(["result"=>"opration faild"],400);
            }
        }
        catch(Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
   }

    public function emailConfirmation($email){
        try{
        $db = (new connection)->social;
        $data = $db->user->findOne(["email"=>$email]);
            if (!empty($data)) {
                $var = $db->user->updateOne(["email"=>$email],
                        ['$set'=>['email_verified_at'=>1
                        ]]);
                if($var->getmodifiedcount()> 0)
                    return response()->success(['data'=>"Your Email Verified Sucessfully!!!"]);
                else
                    return response()->success(['data'=>"Already Verified"],404);
            }else{
                return response()->error(['data'=>"this user is not exist"],202);
            }
        }
        catch(Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }


    function signIn(signinrequest $request){
        try{
            $auth=$request->validated();
            $db = (new connection)->social->user;
            $data = $db->findOne(["email"=>$auth['email']]);

            //dd($user->remember_token);
            if (Hash::check($auth['password'],$data->password))
            {
                if(($data->remember_token==null)){

                    $jwt=(new JwtController)->createToken($data);

                    //save token in

                    $var = $db->updateOne(["email"=>$auth['email']],
                    ['$set'=>['remember_token'=>$jwt
                    ]]);
                    return response()->success(['token'=>$jwt],200);

                }
                else{
                    return response()->error(['data'=>"user alreday sign"],401);

                }
            }
            else{
                return response()->error(['message'=>"email and password not valied"],401);
            }
        }
        catch(Exception $ex){
            return response()->error($ex->getMessage(),401);
        }
    }

        function logOut(Request $request){
            try{
            $token=$request->bearerToken();
            $token=(new JwtController)->decodeToken($token);
            $email=$token->data->email;
            $db = (new connection)->social->user;
            $var = $db->updateOne(["email"=>$email],
            ['$set'=>['remember_token'=>null]]);
        //    $remember_token=$db->remember_token;
        //     dd($remember_token);
            if($var){

                return response()->success(['message'=>"you are successfully logout"],200);
            }
            else{
                return response()->error(['message'=>"there is some problem in logout"],400);
            }
        }
        catch(Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
           }

 }
