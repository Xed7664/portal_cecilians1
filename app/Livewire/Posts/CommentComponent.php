<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\{Post, Comment};
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\{Auth, Gate};

class CommentComponent extends Component
{
    public $comment;
    public $content;

    public $reacted;
    public $reactionCount;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;

        $this->reacted = auth()->check() && $comment->isReactedBy(auth()->user(), 'love');
    }

    public function render()
    {
        $this->reactionCount = $this->comment->reactions->count();

        return view('livewire.posts.comment-component', [
            'comment' => $this->comment,
        ]);
    }

    public function reactToComment()
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            // Check if the comment exists
            if (!$this->comment) {
                $this->dispatch('show-toast', [
                    'title' => 'Error',
                    'message' => 'The comment no longer exists or may have been removed.',
                    'type' => 'danger',
                    'sound' => null
                ]);
                return;
            }

            // Check if the user is authorized to react to the comment
            if (Gate::allows('reactToPost', [Comment::class, $this->comment])) {
                // Update the comment count on the associated post
                $this->comment->post->commentsCount = Comment::where('post_id', $this->comment->post->id)->count();

                // Check if the user has already reacted to the comment
                if ($this->reacted) {
                    // Unreact to the comment
                    $this->comment->unreact('love');

                    // Update the reaction status and count
                    $this->reacted = false;
                    $this->reactionCount = $this->comment->reactions->where('type', 'love')->count();

                    // Dispatch an event for unreacting
                    $this->dispatch('show-toast', [
                        'title' => 'Reaction Removed',
                        'message' => null,
                        'type' => 'success',
                        'sound' => null
                    ]);
                } else {
                    // React to the comment
                    $this->comment->react('love');

                    // Update the reaction status and count
                    $this->reacted = true;
                    $this->reactionCount = $this->comment->reactions->where('type', 'love')->count();

                    // Dispatch an event for reacting
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
                    'message' => 'You are not authorized to give a reaction to this comment. <a href="#" class="learn-more-link">Learn More</a>',
                    'type' => 'danger',
                    'sound' => null,
                ]);
            }
        } else {
            // User is not logged in, show an error message
            $this->dispatch('show-toast', [
                'title' => 'Error',
                'message' => 'You must be logged in to react to a comment.',
                'type' => 'danger',
                'sound' => null
            ]);
        }
    }
}
