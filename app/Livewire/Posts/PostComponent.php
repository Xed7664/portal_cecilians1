<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{Post, Comment, Repost};
use App\Http\Controllers\ReactionController;

class PostComponent extends Component
{
    public $page = 1;
    public $posts = [];
    public $allContentLoaded = false;

    public $postId;
    public $post;

    public $isRepost = false;

    public function mount()
    {
        $this->page = 1;

        // Fetch posts and reposts
        $currentUser = auth()->user();
        $followedUserIds = $currentUser->following->pluck('id');

        $posts = Post::whereIn('user_id', $followedUserIds)
            ->orWhere('user_id', $currentUser->id)
            ->with([
                'user',
                'comments' => function ($query) {
                    $query->latest()->with([
                        'replies' => function ($subQuery) {
                            $subQuery->latest();
                        }
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->where('status', 'publish')
            ->paginate(5, ['*'], 'page', $this->page)
            ->items();

        $reposts = Repost::whereIn('user_id', $followedUserIds)
            ->orWhere('user_id', $currentUser->id)
            ->with([
                'user',
                'originalPost.user',
                'comments' => function ($query) {
                    $query->latest()->with([
                        'replies' => function ($subQuery) {
                            $subQuery->latest();
                        }
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->where('status', 'publish')
            ->paginate(5, ['*'], 'page', $this->page)
            ->items();

        // Combine and merge posts and reposts
        $allPosts = array_merge($posts, $reposts);

        // Sort the combined array by created_at in descending order
        usort($allPosts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        // Limit to 10 posts
        $this->posts = array_slice($allPosts, 0, config('portal.newsfeed.first_posts_count'));

        // Loop through each post and set the reacted flag
        foreach ($this->posts as $post) {
            // $post->reacted = $post->isReactedBy($currentUser, 'love');
            // $post->reactionCount = $post->reactions->where('type', 'love')->count();
            // $post->commentsCount = Comment::where('post_id', $post->id)->count();

            // Increment the view count by 1
            if (is_numeric($post->views_count)) {
                $post->increment('views_count');
            }

        }


    }

    #[On('new-post')]
    public function addPostToTop($data)
    {

        $type = $data['data']['type'];

        switch ($type) {
            case 'repost':
                $newPost = Repost::find($data['data']['id']); // Fetch the new post data
                break;
            
            case 'post':
                $newPost = Post::find($data['data']['id']); // Fetch the new post data
                break;

            // Add more cases as needed

            default:
                // Handle the default case, if needed
                break;
        }

        array_unshift($this->posts, $newPost); // Add the new post to the top of the posts

        // Optionally, limit the number of displayed posts
        $this->posts = array_slice($this->posts, 0, 10); // Limit to 10 posts

        //$this->post = $data;

        //dd($newPost);

        //$this->isSummary = $isSummary;

        // Render the Livewire component to reflect the updated posts
        $this->render();

    }

    public function loadMore()
    {
        $this->page++;
        $newPosts = $this->getPosts();

        if (empty($newPosts)) {
            $this->allContentLoaded = true;
        } else {
            $this->posts = array_merge($this->posts, $newPosts);
           //$this->posts = $this->posts->concat($newPosts);
        }
    }

    protected function getPosts()
    {
        $currentUser = auth()->user();

        $followedUserIds = $currentUser->following->pluck('id');

        $posts = Post::whereIn('user_id', $followedUserIds)
            ->orWhere('user_id', $currentUser->id)
            ->with([
                'user',
                'comments' => function ($query) {
                    $query->latest()->with([
                        'replies' => function ($subQuery) {
                            $subQuery->latest();
                        }
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->where('status', 'publish') // Only include posts with status 'publish'
            ->paginate(5, ['*'], 'page', $this->page)
            ->items();

        foreach ($posts as $post) {
            // Debug the 'views_count' for each post
            //dump($post->views_count);

            $post->reacted = $post->isReactedBy($currentUser, 'love');
            $post->reactionCount = $post->reactions->where('type', 'love')->count();

            // Increment the view count by 1
            if (is_numeric($post->views_count)) {
                $post->increment('views_count');
            }
        }

        return $posts;
    }


    public function render()
    {
        return view('livewire.posts.post-component', [
            'posts' => $this->posts,
        ]);
    }

    // #[On('modal-opened')]
    // public function openModal($data)
    // {
    //     dd('Connected');
    //     $this->postId = $data['postId']; // Set the $postId property
    //     $this->post = Post::find($data['postId']); // Set the $post property
    // }


}