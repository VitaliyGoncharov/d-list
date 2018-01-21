<?php

namespace App\Http\Controllers;

use Auth;
use App\Dislike;
use App\Like;
use Illuminate\Http\Request;


class LikesController extends Controller
{
    public function index(Request $request,Like $like,Dislike $dislike)
    {
        $post_id = $request->input('post_id');
        $user_id = Auth::user()->id;
        $likedOrDisliked = $request->input('action');

        if(!$post_id || !$user_id || !$likedOrDisliked)
        {
            exit();
        }

        // add record if user liked post
        if($likedOrDisliked == 'like')
        {
            $this->add($post_id,$user_id,$like);
            exit();
        }

        // add record if user disliked post
        if($likedOrDisliked == 'dislike')
        {
            $this->add($post_id,$user_id,$dislike);
            exit();
        }

        // delete record if user deleted his like
        if($likedOrDisliked == 'delete like')
        {
            $this->delete($post_id,$user_id,$like);
            exit();
        }

        // delete record if user deleted his dislike
        if($likedOrDisliked == 'delete dislike')
        {
            $this->delete($post_id,$user_id,$dislike);
            exit();
        }
    }

    private function add($post_id,$user_id,$entity)
    {
        $record = $entity->where([
            ['post_id',$post_id],
            ['user_id',$user_id]
        ])->select('id')->get();

        // check if user didn't like or dislike this post before
        // if he didn't then add new record in `likes` | `dislikes` table
        if(!isset($record[0]))
        {
            $entity->post_id = $post_id;
            $entity->user_id = $user_id;
            $entity->save();

            echo 'Like|Dislike was set';
        }

        // get current model name
        $model_namespace = explode('\\',get_class($entity));
        $model_name = end($model_namespace);

        // get the opposite table,
        // maybe user liked or disliked this post before and now he change his choice
        if($model_name === 'Like')
        {
            $opposite = new Dislike();
        }
        else
        {
            $opposite = new Like();
        }

        $oppositeRecord = $opposite->where([
            ['post_id',$post_id],
            ['user_id',$user_id]
        ])->select('id')->get();

        // if user change his choice we need to delete old choice (like | dislike)
        if(isset($oppositeRecord[0]))
        {
            $opposite->where([
                ['post_id',$post_id],
                ['user_id',$user_id]
            ])->delete();

            echo ', Old choice was deleted';
        }
    }

    private function delete($post_id,$user_id,$entity)
    {
        $record = $entity->where([
            ['post_id',$post_id],
            ['user_id',$user_id]
        ])->select('id')->get();

        // if user really liked or disliked this post before,
        // then delete the record
        if(isset($record[0]))
        {
            $entity->where([
                ['post_id',$post_id],
                ['user_id',$user_id]
            ])->delete();

            echo 'Like|Dislike was deleted';
        }
    }
}
