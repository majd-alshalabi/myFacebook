<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\PostRequest;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\DeletePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\UserLike;
use Illuminate\Support\Facades\DB;


class PostController extends Controller
{
    public function addPost(PostRequest $request)
    {
        $post = new Post();
        if($request->file != null)
        {
            $fileName = time().'.'.$request->file->extension();
            $request->file->move(public_path('post_files'), $fileName);
            $post->file = "public/post_file/".$fileName;
        }
        $user_id= $request->user()->id;
        $post->user_id = $user_id ;
        $post->description = $request->description;
        $post->like_number = 0 ;
        $post->type = $request->type;
        $post->comment_number = 0 ;
        $post->save();
        return $this->sendResponse([$post]);
    }

    public function getAllPosts()
    {
        $post = Post::All();
        return $this->sendResponse([$post]);
    }

    public function deletePost(DeletePostRequest $request)
    {
        $post = Post::where('id' , $request->postId)->first();
        if($post == null)
            return $this->sendFaildResponse(['errorMessage' => "post does not exist"]);
        $user_id= $request->user()->id;
        if($post->user_id == $user_id)
        {
            $post->delete();
            return $this->sendResponse(['response' => "post deleted succesfully"]);
        }
        return $this->sendFaildResponse(['errorMessage' => "you cant delete this post becaus its dont belong to you"]);
    }

    public function updatePost(UpdatePostRequest $request)
    {
        $post = Post::where('id' , $request->postId)->first();
        if($post == null)
            return $this->sendFaildResponse(['errorMessage' => "post does not exist"]);
        $user_id= $request->user()->id;
        if($post->user_id == $user_id)
        {
            $post->update(['description'=>$request->description]);
            return $this->sendResponse(['response' => "post updated succesfully"]);
        }
        return $this->sendFaildResponse(['errorMessage' => "you cant delete this post becaus its dont belong to you"]);
    }
    

    public function toggleLikeValue(LikeRequest $request)
    {
        $post = Post::where('id', '=', $request->postId)->first();
        if($post == null)
        {
            return $this->sendFaildResponse(["errorMessage" => "post does not exist"]);   
        }
        $user_id= $request->user()->id;
        $like = DB::select("SELECT * From user_likes Where user_id = $user_id and post_id = $post->id");
        if(count($like) == 0)
        {
            $addedLike = new UserLike();
            $addedLike->user_id = $user_id ;
            $addedLike->post_id = $post->id;
            $addedLike->type = $request->type;
            $addedLike->save();
            Post::where('id',$post->id)->update(['like_number'=>($post->like_number + 1)]);
            $updatedPost = Post::where('id' , $request->postId)->first();
            return $this->sendResponse(["post" => $updatedPost]);
        }
        else 
        {
            UserLike::where('id', '=', $like[0]->id)->first()->delete();
            Post::where('id',$post->id)->update(['like_number'=>($post->like_number - 1)]);
            $updatedPost = Post::where('id' , $request->postId)->first();
            return $this->sendResponse(["post" => $updatedPost]);
        }
    }

}
