<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserComment;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\GetPostCommentsRequest;
use App\Http\Requests\DeleteCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

use Illuminate\Support\Facades\DB;
use App\Models\Post;

class CommentController extends Controller
{
    public function addComment(CommentRequest $request)
    {
        $post = Post::where('id', '=', $request->postId)->first();
        if($post == null)
        {
            return $this->sendFaildResponse(["errorMessage" => "post does not exist"]);   
        }
        $user_id= $request->user()->id;
        $addedComment = new UserComment();
        $addedComment->user_id = $user_id ;
        $addedComment->post_id = $post->id;
        $addedComment->comment = $request->comment;
        $addedComment->like_number = 0 ;
        $addedComment->save();
        $postComments = DB::select("SELECT * From user_comments Where post_id = $post->id");
        return $this->sendResponse(["post" => $post , "comments" => $postComments]);
    }
    public function getPostComment(GetPostCommentsRequest $request)
    {
        $comment = UserComment::where('post_id', '=', $request->postId)->get();
        return $this->sendResponse([$comment]);
    }

    public function deleteComment(DeleteCommentRequest $request)
    {
        $comment = UserComment::where('id' , $request->commentId)->first();
        if($comment == null)
            return $this->sendFaildResponse(['errorMessage' => "comment does not exist"]);
        $user_id= $request->user()->id;
        if($comment->user_id == $user_id)
        {
            $comment->delete();
            return $this->sendResponse(['response' => "comment deleted succesfully"]);
        }
        return $this->sendFaildResponse(['errorMessage' => "you cant delete this comment because its dont belong to you"]);
    
    }
    public function updateComment(UpdateCommentRequest $request)
    {
        $comment = UserComment::where('id' , $request->commentId)->first();
        if($comment == null)
            return $this->sendFaildResponse(['errorMessage' => "comment does not exist"]);
        $user_id= $request->user()->id;
        if($comment->user_id == $user_id)
        {
            $comment->update(['comment'=>$request->comment]);
            return $this->sendResponse(['response' => "comment updated succesfully"]);
        }
        return $this->sendFaildResponse(['errorMessage' => "you cant update this comment because its dont belong to you"]);
    }
    
}
