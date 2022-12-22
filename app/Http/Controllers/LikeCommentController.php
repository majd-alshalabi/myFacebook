<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ToggleLikeCommentRequest;

use App\Models\LikeComment;
use App\Models\UserComment;
use Illuminate\Support\Facades\DB;

class LikeCommentController extends Controller
{
    public function toggleLikeValueForComment(ToggleLikeCommentRequest $request)
    {
        $comment = UserComment::where('id', '=', $request->commentId)->first();
        if($comment == null)
        {
            return $this->sendFaildResponse(["errorMessage" => "comment does not exist"]);   
        }
        $user_id= $request->user()->id;
        $like = DB::select("SELECT * From like_comments Where user_id = $user_id and comment_id = $comment->id");
        if(count($like) == 0)
        {
            $addedLike = new LikeComment();
            $addedLike->user_id = $user_id ;
            $addedLike->comment_id = $comment->id;
            $addedLike->type = $request->type;
            $addedLike->save();
            UserComment::where('id',$comment->id)->update(['like_number'=>($comment->like_number + 1)]);
            $updatedComment = UserComment::where('id' , '=' , $comment->id)->first();
            return $this->sendResponse(["comment" => $updatedComment]);
        }
        else 
        {
            LikeComment::where('id', '=', $like[0]->id)->first()->delete();
            UserComment::where('id',$comment->id)->update(['like_number'=>($comment->like_number - 1)]);
            $updatedComment = UserComment::where('id' , $comment->id)->first();
            return $this->sendResponse(["comment" => $updatedComment]);
        }
    }
}
