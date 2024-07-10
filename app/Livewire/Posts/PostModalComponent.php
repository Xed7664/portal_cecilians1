<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Post;

class PostModalComponent extends Component
{

    public $postId;
    public $post;

    #[On('modal-opened')]
    public function openModal($data)
    {
        $this->postId = $data['postId']; // Set the $postId property
        $this->post = Post::find($data['postId']); // Set the $post property
    }

    public function render()
    {
        return view('livewire.posts.post-modal-component');
    }
}
