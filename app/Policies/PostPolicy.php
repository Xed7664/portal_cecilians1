<?php

namespace App\Policies;

use App\Models\{User, Post, Repost};

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function createPost()
    {
        $user = auth()->user();

        // Allow members with 'create_post' permission to create posts
        if ($user->status === 'member' && $user->hasPermission('create_post')) {
            return true;
        }

        // Allow unverified users with 'create_post' permission to create posts with restrictions
        if ($user->status === 'unverified' && $user->hasPermission('create_post')) {
            // Implement restrictions for unverified users if needed
            return true; // For example, you might allow them to create, but with moderation
        }

        // Deny access for banned users
        if ($user->status === 'banned') {
            return false;
        }
    }

    public function createComment()
    {
        $user = auth()->user();

        // Allow members with 'create_comment' permission to comment
        if ($user->status === 'member' && $user->hasPermission('create_comment')) {
            return true;
        }

        // Allow unverified users with 'create_comment' permission to comment with restrictions
        if ($user->status === 'unverified' && $user->hasPermission('create_comment')) {
            // Implement restrictions for unverified users if needed
            return true; // For example, you might allow them to comment, but with moderation
        }

        // Deny access for banned users
        if ($user->status === 'banned') {
            return false;
        }
    }

    public function reactToPost()
    {
        $user = auth()->user();

        // Allow members with 'react_to_post' permission to react to posts
        if ($user->status === 'member' && $user->hasPermission('react_to_post')) {
            return true;
        }

        // Allow unverified users with 'react_to_post' permission to react with restrictions
        if ($user->status === 'unverified' && $user->hasPermission('react_to_post')) {
            // Implement restrictions for unverified users if needed
            return true; // For example, you might allow them to react, but with moderation
        }

        // Deny access for banned users
        if ($user->status === 'banned') {
            return false;
        }
    }

    public function deletePost(User $user, Post $post)
    {
        //$user = auth()->user();
    
        return $user->id === $post->user_id && $user->hasPermission('delete_post');
    }

    public function deleteRepost(User $user, Repost $post)
    {
        //$user = auth()->user();
    
        return $user->id === $post->user_id && $user->hasPermission('delete_post');
    }


    public function deleteComment(Comment $comment)
    {
        $user = auth()->user();

        // Authorization logic for deleting comments
        // Example: Only allow the comment owner to delete it
        return $user->id === $comment->user_id && $user->hasPermission('delete_comment');
    }

    public function deleteReply(Reply $reply)
    {
        $user = auth()->user();

        // Authorization logic for deleting replies
        // Example: Only allow the reply owner to delete it
        return $user->id === $reply->user_id && $user->hasPermission('delete_reply');
    }
}
