<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtController
{
    public function createToken($user)
    {
        try{
        $iss = "localhost";
        $iat = time();
        $nbf = time();
        // $exp = $iat +4550;
        $aud = 'app user';
        $data=[
            "id"=>(string)$user['_id'],
            "name"=>$user['name'],
            "email"=>$user['email'],
            "password"=>$user['password']
        ];

        $secret_key = "owt125";
        $payload_info=array(
            "iss"=> $iss,
            "iat"=> $iat,
            "nbf"=> $nbf,
           // "exp"=> $exp,
            "aud"=> $aud,
            "data"=>$data,
        );
        $jwt = JWT::encode($payload_info, $secret_key,'HS512');
        return $jwt;
    }
    catch(Exception $ex){
        return response()->error($ex->getMessage());
    }
    }
         public function decodeToken($token)
         {
             try{
            $decoded_data = JWT::decode($token, new key("owt125","HS512"));
            return $decoded_data;
             }
             catch(Exception $ex){
                 return response()->error($ex->getMessage());
             }
         }
}
