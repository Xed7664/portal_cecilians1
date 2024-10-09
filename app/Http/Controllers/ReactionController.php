<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reply;


class ReactionController extends Controller
{
    public function reactToPost(Post $post, $type)
    {
        $user = auth()->user();

        // Check if the user has already reacted with the same type
        if ($post->isReactedBy($user, $type)) {
            // User is unreacting, remove the reaction
            $post->unreact($type);
            $count = $post->reactions()->where('type', $type)->count();
            return response()->json(['result' => true, 'reacting' => false, 'message' => 'Unreacted successfully.', 'data' => ['count' => $count]]);
        } else {
            // User is reacting with the selected type
            $post->react($type);
            $count = $post->reactions()->where('type', $type)->count();
            return response()->json(['result' => true, 'reacting' => true, 'message' => 'Reacted successfully.', 'data' => ['count' => $count]]);
        }
    }

    public function reactToComment(Comment $comment, $type)
    {
        $user = auth()->user();

        // Check if the user has already reacted with the same type
        if ($comment->isReactedBy($user, $type)) {
            // User is unreacting, remove the reaction
            $comment->unreact($type);
            $count = $comment->reactions()->where('type', $type)->count();
            return response()->json(['result' => true, 'reacting' => false, 'message' => 'Unreacted successfully.', 'data' => ['count' => $count]]);
        } else {
            // User is reacting with the selected type
            $comment->react($type);
            $count = $comment->reactions()->where('type', $type)->count();
            return response()->json(['result' => true, 'reacting' => true, 'message' => 'Reacted successfully.', 'data' => ['count' => $count]]);
        }
    }

    public function reactToReply(Reply $reply, $type)
    {
        $user = auth()->user();

        // Check if the user has already reacted with the same type
        if ($reply->isReactedBy($user, $type)) {
            // User is unreacting, remove the reaction
            $reply->unreact($type);
            $count = $reply->reactions()->where('type', $type)->count();
            return response()->json(['result' => true, 'reacting' => false, 'message' => 'Unreacted successfully.', 'data' => ['count' => $count]]);
        } else {
            // User is reacting with the selected type
            $reply->react($type);
            $count = $reply->reactions()->where('type', $type)->count();
            return response()->json(['result' => true, 'reacting' => true, 'message' => 'Reacted successfully.', 'data' => ['count' => $count]]);
        }
    }




}
