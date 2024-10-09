<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\{Post, User, UserPortalNotification};
use App\Livewire\Posts\{PostComponent, Sidebar\TrendingHashtags};
use Illuminate\Support\Facades\Gate;
use App\Policies\PostPolicy;
use App\Jobs\CreateUserPortalNotificationsJob;

class CreatePost extends Component
{
    public $postContent = '';
    public $isPosting = false;

    public function createPost()
    {
        
        // Set the loading state to true
        $this->isPosting = true;
        
        // Check if the user is logged in
        if (auth()->check()) {
            // Get the authenticated user
            $user = auth()->user();

            // Use the PostPolicy to check if the user is authorized to create a post
            if (Gate::allows('createPost', [Post::class, $user])) {
                // The user is authorized, create the post

                // Trim the comment content to remove leading and trailing spaces
                $trimmedContent = trim($this->postContent);
                
                if (is_null($trimmedContent) || empty($trimmedContent)) {
                    // Content is not allowed, return a dispatch with a danger message
                    return $this->dispatch('show-toast', [
                        'title' => 'Invalid Content',
                        'message' => 'Content cannot be empty or invalid.',
                        'type' => 'danger',
                        'sound' => null,
                    ]);
                }


                // Create a new post for the user
                $post = new Post([
                    'user_id' => $user->id,
                    'content' => $trimmedContent,
                    'status' => 'publish', // or any other status
                    'disable_comment' => false, // or set it to true as needed
                ]);

                $post->save();

                // Extract mentions and hashtags from the post content
                $mentions = []; // Collect mentions
                $hashtags = []; // Collect hashtags

                // Use regular expressions to find mentions and hashtags in the post content
                $pattern = "/\B@(\w+)\b/";
                if (preg_match_all($pattern, $trimmedContent, $matches)) {
                    $mentions = $matches[1];
                }

                $pattern = "/\B#(\w+)\b/";
                if (preg_match_all($pattern, $trimmedContent, $matches)) {
                    $hashtags = $matches[1];
                }

                // Handle mentions and hashtags
                foreach ($mentions as $mention) {
                    // Find the user by username or ID (adjust your logic here)
                    $mentionedUser = User::where('username', $mention)->first();
                    if ($mentionedUser) {
                        $post->addMention($mentionedUser); // Use the addMention method

                        // Notify mentioned user with a portal notification
                        $this->notifyMentionedUser($user, $mentionedUser, $post);
                    }
                }

                foreach ($hashtags as $hashtag) {
                    $post->addHashtag($hashtag); // Use the addHashtag method
                }

                // Clear the post content after creating the post
                $this->postContent = '';
                
                $post->type = 'post';

                // Create a user portal notification for followers
                $this->createUserPortalNotifications($user, $post);

                // Dispatch the job to create user portal notifications and notify mentioned users
                //CreateUserPortalNotificationsJob::dispatch($user, $post->mentions->toArray(), 1, $trimmedContent);

                // Update the post
                $this->dispatch('new-post', ['data' => $post])->to(PostComponent::class);

                // Update trending as well
                $this->dispatch('update-trends')->to(TrendingHashtags::class);

                // Dispatch a Livewire event to show a toast notification
                $this->dispatch('show-toast', [
                    'title' => 'Post Added',
                    'message' => 'Your post has been added',
                    'type' => 'success',
                    'sound' => 'pop.mp3',
                ]);
            } else {
                // The user is not authorized, show an error message
                $this->dispatch('show-toast', [
                    'title' => 'Unauthorized',
                    'message' => 'You are not authorized to create a post. <a href="#" class="learn-more-link">Learn More</a>',
                    'type' => 'danger',
                    'sound' => null,
                ]);
            }
        } else {
            $this->dispatch('show-toast', [
                'title' => 'Login Required',
                'message' => 'You must be logged in to create a post',
                'type' => 'danger',
                'sound' => null,
            ]);
        }

         // After the post is created, set the loading state back to false
         $this->isPosting = false;
    }

    public function render()
    {
        return view('livewire.posts.create-post');
    }

    protected function createUserPortalNotifications(User $user, Post $post)
    {
        // Get the followers of the user who posted the content
        $followers = $user->followers;

        // Iterate through followers and create user portal notifications
        foreach ($followers as $follower) {
            $notificationData = [
                'portal_notification_id' => 1,
                'sender_id' => $user->id,
                'user_id' => $follower->id,
                'type' => 'post',
                'related_id' => $post->id,
            ];

            UserPortalNotification::create($notificationData);
        }
    }

    protected function notifyMentionedUser(User $sender, User $mentionedUser, Post $post)
    {
        $notificationData = [
            'portal_notification_id' => 4,
            'sender_id' => $sender->id,
            'user_id' => $mentionedUser->id,
            'type' => 'post',
            'related_id' => $post->id,
        ];

        UserPortalNotification::create($notificationData);
    }
}
