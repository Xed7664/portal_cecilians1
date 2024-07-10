<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Post, Hashtag, CommentHashtag, Comment, ReplyHashtag, Reply, User, Repost};

class PostController extends Controller
{
    public function index()
    {
        // Retrieve the currently authenticated user
        $currentUser = auth()->user();

        // Retrieve posts from the database, focusing on posts from followed users
        $followedUserIds = $currentUser->following->pluck('id');
        
        // Include the currently authenticated user's own posts
        $posts = Post::withTrashed()
        ->whereIn('user_id', $followedUserIds)
        ->orWhere('user_id', $currentUser->id)
        ->with(['user', 'comments' => function ($query) {
            // Select the latest comment for each post, if it exists
            $query->latest()->with(['replies' => function ($subQuery) {
                // Select the latest reply for the latest comment, if it exists
                $subQuery->latest();
            }]);
        }])
        ->orderBy('created_at', 'desc') // Order by created_at in descending order
        ->where('status', 'publish')
        ->paginate(10, ['*'], 'page', 1)
        ->items();

        foreach($posts as $post){
            if (is_numeric($post->views_count)) {
                $post->increment('views_count');
            }
        }

        // Fetch reposts for the posts
        $reposts = Repost::whereIn('post_id', collect($posts)->pluck('id')->toArray())
            ->with(['user'])
            ->whereHas('post', function ($query) {
                $query->where('status', 'publish');
            })
            ->orderByDesc('created_at')
            ->get();

        // Merge the reposts with the posts based on the created_at timestamp
        $mergedData = collect($posts)->merge($reposts)->sortByDesc('created_at')->all();

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
        
        // Pass the sorted $allHashtags to the view
        return view('posts.index', ['posts' => $mergedData, 'trendingHashtags' => $allHashtags]);

    }

    public function show($username, $post_id)
    {
        // Retrieve the post from the database using the actual post ID, and ensure it has a status of "published"
        $post = Post::where('id', $post_id)
        ->where('status', 'publish')
        ->firstOrFail();

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
