<?php

namespace App\Livewire\Posts\Modals;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\{Post, User, Comment, Repost, UserPortalNotification};
use App\Livewire\Posts\{Single, Sidebar\TrendingHashtags};

class ReplyComponent extends Component
{
    public $post;

    public $isRepost = false; 

    public $content;

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

        $this->content = '';

    }

    public function reply()
    {
        $this->validate([
            'content' => 'required|min:3', // Add any validation rules you need
        ]);

        $this->isRepost = $this->post instanceof Repost;

        $user = auth()->user();
        
        if ($this->isRepost) { 
            $target_postId = null;
            //$target_repostId = $this->post->originalPost->id;
            $target_repostId = $this->post->id;
        } else {
            $target_postId = $this->post->id;;
            $target_repostId = null;
        }

        $comment = new Comment([
            'user_id' => $user->id,
            'post_id' => $target_postId,
            'repost_id' => $target_repostId,
            'content' => $this->content,
        ]);

        $this->post->comments()->save($comment);

        // Extract hashtags from the comment content
        $hashtags = [];
        $pattern = "/\B#(\w+)\b/";
        if (preg_match_all($pattern, $this->content, $matches)) {
            $hashtags = $matches[1];
        }

        // Handle hashtags
        foreach ($hashtags as $hashtag) {
            $comment->addHashtag($hashtag); // Use the addHashtag method
        }

        // BUG: The stats are not updating (FIXED)
        
        if ($this->isRepost) { 
            $this->dispatch('refresh-component', ['postId' => $target_repostId])->to(Single::class);
        } else {
            $this->dispatch('refresh-component', ['postId' => $target_postId])->to(Single::class);
        }

        // Update trending as well
        $this->dispatch('update-trends')->to(TrendingHashtags::class);

        // Clear the input after adding a comment
        $this->content = '';

        // Sending notification
        if ($this->isRepost) { 
            // Repost
            $receiver = $this->post->originalPost->user_id; // Use $this->post
            $this->notifyUser(6, $user->id, $receiver, $target_repostId);
        } else {
            // Post
            $receiver = $this->post->user_id; // Use $this->post
            $this->notifyUser(6, $user->id, $receiver, $target_postId);
        }

        // Optionally, you can emit an event to refresh the component
        $this->dispatch('show-toast', [
            'title' => 'Reply Added',
            'message' => 'Reply added. <a href="' . url('./profile/' . $this->post->user->username . '/posts/'. $this->post->id .'?reply='.$comment->id) . '" class="fw-bold ms-1 text-success-emphasis">View</a>',
            'type' => 'success',
            'sound' => null,
        ]);
        
    }

    protected function notifyUser($notificationId, $senderId, $receiverId, $postId)
    {
        
        // 6 = <b>{Users.username}</b> commented on your post.
        // 8 = <b>{Users.username}</b> mentioned you on their comment
        $notificationData = [
            'portal_notification_id' => $notificationId,
            'sender_id' => $senderId,
            'user_id' => $receiverId,
            'type' => 'repost',
            'related_id' => $postId,
        ];

        UserPortalNotification::create($notificationData);
    }


    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function render()
    {
        return view('livewire.posts.modals.reply-component');
    }
}
