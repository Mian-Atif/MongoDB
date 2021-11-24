<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MongoDB\Client as connection;

class UserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token=$request->bearerToken();
        $db = (new connection)->social->user;

        $result = $db->findOne(["remember_token"=>$token]);

        if(isset($result)){
            return $next($request);
        }else{
            return response()->error(['data'=>"token expire!"],400);
        }

    }
}
