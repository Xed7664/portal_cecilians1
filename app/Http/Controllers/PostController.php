<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\{Post, Hashtag, CommentHashtag, Comment, ReplyHashtag, Reply, User, Repost};

class PostController extends Controller
{
    public function index()
    {
        // Fetch trending hashtags
        $trendingHashtags = $this->getTrendingHashtags();

        return view('posts.index', ['trendingHashtags' => $trendingHashtags]);
    }

     // Helper function to merge and sort multiple collections
     private function mergeAndSort($collections, $sortBy)
     {
         $merged = Collection::empty();
 
         foreach ($collections as $collection) {
             $merged = $merged->merge($collection);
         }
 
         return $merged->sortByDesc($sortBy)->values()->all();
     }
 
     // Helper function to get trending hashtags
     private function getTrendingHashtags()
     {
         $hashtags = Hashtag::get();
         $hashtags = $hashtags->map(function ($hashtag) {
             $hashtag->count = $hashtag->posts->count();
             return $hashtag;
         });
 
         return $hashtags->sortByDesc('count')->take(10);
     }

    public function show($username, $post_id)
    {
        if (Post::where('id', '=', $post_id)->exists()) {
            $post = Post::where('id', $post_id)
                    ->where('status', 'publish')
                    ->firstOrFail();
        }

        if (Repost::where('id', '=', $post_id)->exists()) {
            $post = Repost::where('id', $post_id)
                    ->where('status', 'publish')
                    ->firstOrFail();
        }

        // Ang owner kay dili mao ang tag-iya.
        if($post->user->username != $username){
            abort(404); 
        }

        // Fetch trending hashtags from comments
        //$commentHashtags = CommentHashtag::take(10)->get();
        $commentHashtags = CommentHashtag::get();

        // Count hashtags from comments
        $commentHashtags = $commentHashtags->map(function ($hashtag) {
            return [
                'tag' => $hashtag->hashtag->tag, // Assuming you have a relationship set up
                'count' => Comment::whereHas('hashtags', function ($query) use ($hashtag) {
                    $query->where('hashtag_id', $hashtag->hashtag_id);
                })->count(),
            ];
        });


        // Fetch trending hashtags from replies
        // $replyHashtags = ReplyHashtag::take(10)->get();
        $replyHashtags = ReplyHashtag::get();

        // Count hashtags from replies
        $replyHashtags = $replyHashtags->map(function ($hashtag) {
            return [
                'tag' => $hashtag->hashtag->tag, // Assuming you have a relationship set up
                'count' => Reply::whereHas('hashtags', function ($query) use ($hashtag) {
                    $query->where('hashtag_id', $hashtag->hashtag_id);
                })->count(),
            ];
        });

        // Fetch trending hashtags from posts
        // $trendingHashtags = Hashtag::take(10)->get();
        $trendingHashtags = Hashtag::get();

        // Count hashtags from posts
        $trendingHashtags = $trendingHashtags->map(function ($hashtag) {
            $hashtag->count = $hashtag->posts->count();
            return $hashtag;
        });

        // Merge and sort the counts from all sources
        $allHashtags = $trendingHashtags->concat($commentHashtags)->concat($replyHashtags);
        $allHashtags = $allHashtags->groupBy('tag')->map(function ($hashtags) {
            return $hashtags->sum('count');
        })->map(function ($count, $tag) {
            return [
                'tag' => $tag,
                'count' => $count,
            ];
        })->sortByDesc('count')->take(10);
        

        // Pass the post data to the view
        return view('posts.show', ['post' => $post, 'trendingHashtags' => $allHashtags]);
    }

    function areMutualFollowers(User $user1, User $user2) {
        if ($user1->id === $user2->id) {
            return true; // Users cannot be mutual followers of themselves
        }
        
        return $user1->isFollowing($user2) && $user2->isFollowing($user1);
    }


}
