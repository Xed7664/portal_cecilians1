<?php

namespace App\Livewire\Posts\Sidebar;

use Livewire\Component;
use App\Models\{CommentHashtag, PostHashtag, RepostHashtag, ReplyHashtag, Hashtag}; 
use Livewire\Attributes\On;

class TrendingHashtags extends Component
{
    public $trendingHashtags;

    public function mount()
    {
        $this->loadTrendingHashtags();
    }

    #[On('update-trends')]
    public function updateTrends()
    {
        $this->loadTrendingHashtags();
    } 

    public function loadTrendingHashtags()
    {
        // Get hashtags from different sources created within the current week
        $allHashtags = Hashtag::whereHas('posts', function ($query) {
                $query->where('posts.created_at', '>=', now()->startOfWeek());
            })
            ->orWhereHas('reposts', function ($query) {
                $query->where('reposts.created_at', '>=', now()->startOfWeek());
            })
            ->orWhereHas('comments', function ($query) {
                $query->where('comments.created_at', '>=', now()->startOfWeek());
            })
            ->orWhereHas('replies', function ($query) {
                $query->where('replies.created_at', '>=', now()->startOfWeek());
            })
            ->withCount(['posts', 'reposts', 'comments', 'replies'])
            ->get();

        // Additional filtering for the current month
        $allHashtags = $allHashtags
            ->filter(function ($hashtag) {
                return $hashtag->created_at >= now()->startOfMonth();
            })
            ->map(function ($hashtag) {
                // Add count property for trending
                $hashtag['count'] = $hashtag->posts_count +
                    $hashtag->reposts_count +
                    $hashtag->comments_count +
                    $hashtag->replies_count;

                return $hashtag;
            })
            ->sortByDesc('count')
            ->take(config('portal.newsfeed.trends_to_follow_count'));

        $this->trendingHashtags = $allHashtags;
    }

    public function render()
    {
        return view('livewire.posts.sidebar.trending-hashtags');
    }
}
