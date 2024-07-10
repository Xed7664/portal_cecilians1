<?php

namespace App\Livewire\Posts\Modals;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\{Post, Repost};
use Illuminate\Support\Facades\{Auth};

class ViewComponent extends Component
{

    public $post;

    public $isRepost = false; 

    public $reactionCount = 0;
    public $repostCount = 0;
    public $replyCount = 0;
    public $viewsCount = 0;

    public $showModal = false;

    #[On('model-opened')] 
    public function openModal($postId)
    {
        $this->showModal = true;

        if (Post::where('id', '=', $postId)->exists()) {
            $this->post = Post::findOrFail($postId)->first();
        }

        if (Repost::where('id', '=', $postId)->exists()) {
            $this->post = Repost::findOrFail($postId)->first();
        }

        $this->reactionCount = $this->post->reactions->count();
        $this->repostCount = $this->post->reactions->count();
        $this->replyCount = $this->post->comments->count();
        $this->viewsCount = number_format($this->post->views_count);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reactionCount = 0;
        $this->repostCount = 0;
        $this->replyCount = 0;
        $this->viewsCount = 0;
    }

    public function render()
    {
        return view('livewire.posts.modals.view-component');
    }
}
