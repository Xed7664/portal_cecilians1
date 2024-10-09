<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\{Post, Comment, User, Repost};
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;

class SingleFull extends Component
{
    public $post;
    public User $user;

    public bool $isRepost = false;

    public $reacted;
    public $reactionCount;

    public $comments;
    public $commentsCount;

    public bool $isSummary;

    // Commenting
    public $commentContent;

    public function mount($post, $isSummary)
    {

        $tableName = $post->getTable();

        switch ($tableName) {
            case 'posts':
                // Code for handling posts table
                $this->isRepost = false;
                $this->post = Post::findOrFail($post->id);
                break;

            case 'reposts':
                // Code for handling reposts table
                $this->isRepost = true;
                $this->post = Repost::findOrFail($post->id);
                break;

            default:
                // Handle other cases or throw an exception if needed
                throw new \Exception("Unknown table: $tableName");
        }
        
        // $this->post = Post::findOrFail($post->id);

        $this->post->user = User::findOrFail($post->user_id);

        $this->isSummary = $isSummary;

        $this->reacted = auth()->check() && $post->isReactedBy(auth()->user(), 'love');
        $this->comments = Comment::where('post_id', $post->id)->get();

        $this->post->commentsCount = Comment::where('post_id', $post->id)->count();
    }

    public function addComment()
    {
        $this->validate([
            'commentContent' => 'required|min:3', // Add any validation rules you need
        ]);

        $user = auth()->user();

        $comment = new Comment([
            'user_id' => $user->id,
            'post_id' => $this->post->id,
            'content' => $this->commentContent,
        ]);

        $this->post->comments()->save($comment);

        // Clear the input after adding a comment
        $this->commentContent = '';

        // Optionally, you can emit an event to refresh the component
        $this->dispatch('show-toast', [
            'title' => 'Reply Added',
            'message' => 'Reply added',
            'type' => 'success',
            'sound' => null,
        ]);
    }

    public function render()
    {
        $this->reactionCount = $this->post->reactions->count();
        $this->commentsCount = $this->post->comments->count();

        $postWithCommentsAndReplies = Post::where('id', $this->post->id)
        ->where('status', 'publish') // Exclude soft-deleted posts
        ->with([
            'comments' => function ($query) {
                if ($this->isSummary) {
                    // When isSummary is true, load only 1 comment and 1 reply
                    $query->latest()->take(1)->with([
                        'replies' => function ($subQuery) {
                            $subQuery->latest()->take(1);
                        }
                    ]);
                } else {
                    // When isSummary is false, load all comments and replies
                    $query->with('replies');
                }
            },
            'comments.user', // Load user information for comments
            'comments.reactions', // Load reactions for comments
        ])
        ->first();

        return view('livewire.posts.single-full', [
            'post' => $postWithCommentsAndReplies, // Pass the modified post to the view
        ]);
    }

    public function reactToPost()
    {
        if (auth()->check()) {
            // Check if the post exists
            if (!$this->post) {
                $this->dispatch('show-toast', [
                    'title' => 'Error',
                    'message' => 'The content no longer exists or may have been removed.',
                    'type' => 'danger',
                    'sound' => null
                ]);
                return;
            }

            if (Gate::allows('reactToPost', [Post::class, $this->post])) {
                // Update post count
                $this->post->commentsCount = Comment::where('post_id', $this->post->id)->count();

                // Check if the user has already reacted to the post
                if ($this->reacted) {
                    // Unreact to the post
                    $this->post->unreact('love', auth()->user());

                    // Update the reaction status and count
                    $this->reacted = false;
                    $this->reactionCount = $this->post->reactions->where('type', 'love')->count();

                    // Dispatch an event for unreacting
                    $this->dispatch('show-toast', [
                        'title' => 'Reaction Removed',
                        'message' => null,
                        'type' => 'success',
                        'sound' => null
                    ]);

                } else {
                    // React to the post
                    $this->post->react('love', auth()->user());

                    // Update the reaction status and count
                    $this->reacted = true;
                    $this->reactionCount = $this->post->reactions->where('type', 'love')->count();

                    // Dispatch an event for unreacting
                    $this->dispatch('show-toast', [
                        'title' => 'Reaction Added',
                        'message' => null,
                        'type' => 'success',
                        'sound' => 'positive-btn.mp3'
                    ]);

                }

            } else {
                // The user is not authorized, show an error message
                $this->dispatch('show-toast', [
                    'title' => 'Unauthorized',
                    'message' => 'You are not authorized to give reaction. <a href="#" class="learn-more-link">Learn More</a>',
                    'type' => 'danger',
                    'sound' => null,
                ]);
            }
        } else {
            $this->dispatch('show-toast', [
                'title' => 'Error',
                'message' => 'You must be logged in to react to a comment.',
                'type' => 'danger',
                'sound' => null
            ]);
        }
    }
}
