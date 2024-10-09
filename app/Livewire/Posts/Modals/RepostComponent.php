<?php

namespace App\Livewire\Posts\Modals;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\{Post, Repost, User, UserPortalNotification};
use App\Livewire\Posts\{Single, PostComponent, Sidebar\TrendingHashtags};
use Illuminate\Support\Facades\{Auth};

class RepostComponent extends Component
{
    public $post;

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

        //$this->postContent = '';

    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function repost()
    {
        // Perform repost logic here
        $newRepost = Repost::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(), // Assuming you are using authentication
            'content' => $this->content,
            'status' => 'publish', // or any other status
            'disable_comment' => false, // or set it to true as needed
        ]);

         // Set the 'type' key to indicate it's a repost
        $newRepost->type = 'repost';

        // Extract mentions and hashtags from the post content
        $mentions = []; // Collect mentions
        $hashtags = []; // Collect hashtags

        // Use regular expressions to find mentions and hashtags in the post content
        $pattern = "/\B@(\w+)\b/";
        if (preg_match_all($pattern, $this->content, $matches)) {
            $mentions = $matches[1];
        }

        $pattern = "/\B#(\w+)\b/";
        if (preg_match_all($pattern, $this->content, $matches)) {
            $hashtags = $matches[1];
        }

        $user = auth()->user();

        // Handle mentions and hashtags
        foreach ($mentions as $mention) {
            // Find the user by username or ID (adjust your logic here)
            $mentionedUser = User::where('username', $mention)->first();
            if ($mentionedUser) {
                //$newRepost->addMention($mentionedUser); // Use the addMention method

                // Notify mentioned user with a portal notification
                $this->notifyMentionedUser($user, $mentionedUser, $newRepost);
            }
        }


        //  // Extract hashtags from the repost content
        // $hashtags = [];
        // $pattern = "/\B#(\w+)\b/";
        // if (preg_match_all($pattern, $this->content, $matches)) {
        //     $hashtags = $matches[1];
        // }

        // Attach hashtags to the repost using the addHashtag method
        foreach ($hashtags as $hashtag) {
            $newRepost->addHashtag($hashtag);
        }

        $this->dispatch('new-post', ['data' => $newRepost])->to(PostComponent::class);

        // Update trending as well
        $this->dispatch('update-trends')->to(TrendingHashtags::class);

        $this->dispatch('show-toast', [
            'title' => 'Post Added',
            'message' => 'Your post has been added',
            'type' => 'success',
            'sound' => 'pop.mp3',
        ]);

        // BUG: The stats are not updating (FIXED)
        $this->dispatch('refresh-component', ['postId' => $this->post->id])->to(Single::class);
        
    }

    public function render()
    {
        return view('livewire.posts.modals.repost-component');
    }

    protected function notifyMentionedUser(User $sender, User $mentionedUser, Repost $post)
    {
        $notificationData = [
            'portal_notification_id' => 4,
            'sender_id' => $sender->id,
            'user_id' => $mentionedUser->id,
            'type' => 'repost',
            'related_id' => $post->id,
        ];

        UserPortalNotification::create($notificationData);
    }
}
