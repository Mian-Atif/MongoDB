<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use MongoDB\Client as connection;
use App\Service\JwtController;
use App\Http\Requests\addpost;
use App\Http\Requests\addfriend;
use App\Http\Requests\addcomment;
use App\Http\Requests\updatecomment;
use App\Http\Requests\deletecomment;
class UserPost extends Controller
{
    function addPost(addpost $request){
        try
        {
         $token=$request->bearerToken();
        $auth=$request->validated();
        $db = (new connection)->social->posts;

        if(!empty($token)){
        $token=(new JwtController)->decodeToken($token);
        $id=$token->data->id;
        $name=$token->data->id;
        if(!empty($id)){
        $file=$auth['file']->store($name);
        $db->insertOne([
            'user_id'=>$id,
            'caption'=>$auth['caption'],
            'body'=>$auth['body'],
            'file'=>$file,
            'visibility'=>$auth['visibility']
        ]);
        return response()->success(['data'=>"your post added successfuly"],200);
        }
        else{
            return response()->error(['data'=>"there is some server error please try again"],500);
        }
        }
        else
        return response()->json(['error'=>"token expire"],404);
        }
        catch(Exception $ex)
        {
            return response()->error($ex->getMessage(),400);
        }
    }

    function addFriend(addfriend $request){
        try{
        $token=$request->bearerToken();
        $auth=$request->validated();
        if(!empty($token)){
        $token=(new JwtController)->decodeToken($token);
        $id=new \MongoDB\BSON\ObjectId($token->data->id);
        $db = (new connection)->social->user;
        $friend_id=new \MongoDB\BSON\ObjectId($auth['friend_id']);
        if($id!=$friend_id){
        $friend = array(
            "_id" => new \MongoDB\BSON\ObjectId(),
            "user_id" =>$id,
            "friend_id"=>$friend_id
        );
        $db->updateOne(["_id" => $id],['$push'=>["friends" => $friend]]);
        return response()->success(['data'=>"friend added successfuly"],200);
    }
    else{
        return response()->error(['data'=>"server error"],500);
    }
        }
        else{
        return response()->error(['data'=>"token expire please login again"],401);
        }
    }
    catch(Exception $ex){
        return response()->error($ex->getMessage(),400);
    }
    }


    function addComment(addcomment $request){
        try{
            $token=$request->bearerToken();
            $auth=$request->validated();
        $post_id= new \MongoDB\BSON\ObjectId($auth['post_id']);
        $file=$auth['file']->store($post_id);
        $db = (new connection)->social->posts;
        if(!empty($token)){
        $token=(new JwtController)->decodeToken($token);
        $user_id=$token->data->id;
        if(!empty($user_id)){
        $comment = array(
            "_id" => new \MongoDB\BSON\ObjectId(),
            "user_id" =>$user_id,
            "file" => $file,
            "comment" => $auth['comment']
        );
        $db->updateOne(["_id" => $post_id],['$push'=>["comments" => $comment]]);
        return response()->success(['data'=>"comment added successfuly"],200);
        }
        else{
            return response()->error(['data'=>"user does not exit"],404);
        }
        }
        else{
        return response()->error(['data'=>"token expire please login again"],202);
        }
    }
    catch(Exception $ex){
        return response()->error($ex->getMessage(),400);
    }
    }

    function updateComment(updatecomment $request){
        try{
        $token=$request->bearerToken();
        $auth=$request->validated();
        $post_id= new \MongoDB\BSON\ObjectId($auth['post_id']);
        $comment_id= new \MongoDB\BSON\ObjectId($auth['comment_id']);
        $new_comment=$auth['new_comment'];
        //$file=$request->file('file')->store($post_id);
        $db = (new connection)->social->posts;
        if(!empty($token)){
        $token=(new JwtController)->decodeToken($token);
       // $user_id=$token->data->id;
        $user =$db->findOne(["_id"=>$post_id]);
        if(!empty($user)){

        $db->updateOne(["_id" => $post_id,'comments._id'=>$comment_id],['$set'=>['comments.$.comment' => $new_comment]]);
        return response()->success(['data'=>"comment update successfuly"],200);
        }
        else{
            return response()->error(['data'=>"post does not exit"],404);
        }
        }
        else{
        return response()->error(['data'=>"token expire please login again"],202);
        }
    }
    catch(Exception $ex){
    return response()->error($ex->getMessage(),400);
    }
    }


    function deleteComment(deletecomment $request){
        try{
        $token=$request->bearerToken();
        $auth=$request->validated();
        $post_id= new \MongoDB\BSON\ObjectId($auth['post_id']);
        $comment_id= new \MongoDB\BSON\ObjectId($auth['comment_id']);
        $db = (new connection)->social->posts;
        if(!empty($token)){
        $token=(new JwtController)->decodeToken($token);
        $user =$db->findOne(["_id"=>$post_id]);
        if(!empty($user)){

        $db->updateOne(["_id" => $post_id,'comments._id'=>$comment_id],['$pull'=>['comments' =>['_id'=>$comment_id] ]]);
        return response()->success(['data'=>"comment delete successfuly"],200);
        }
        else{
            return response()->error(['data'=>"post does not exit"],404);
        }
        }
        else{
        return response()->error(['data'=>"token expire please login again"],202);
        }
      }
        catch(Exception $ex){
            return response()->error($ex->getMessage(),400);
              }
    }
}
