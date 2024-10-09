<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\{User, Mention, Post, Comment, CommentHashtag, Reply, Hashtag, Repost, UserPortalNotification};
use Illuminate\Support\Facades\Gate;
use App\Livewire\Posts\PostComponent;
use App\Livewire\Posts\Modals\{ViewComponent, RepostComponent, ReplyComponent};
use App\Http\Controllers\ReactionController;
use Livewire\Attributes\On; 

class Single extends Component
{
    public $post;
    public $isRepost = false; 

    public $reactionCount;
    public $reacted;

    public $reactedComments = [];
    public $reactionCounts = [];
    
    public $comments;
    public $commentsCount;

    public $repostsCount;

    public $commentContent = ''; 
    public $replyContents = [];


    public $submitButtonVisibility = 'none';

    public $showReplyForm = null;
    public $mentionedUsername = null;

    public $isModalOpen = false;

    public $isSummary;

    public function mount($post, $isSummary)
    {
        $this->post = $post;
        $this->isRepost = $this->post instanceof Repost;

        $this->comments = Comment::where('post_id', $post->id)->get();
        
        $this->post->commentsCount = Comment::where('post_id', $post->id)->count();

        // If it's not a repost, try to find it as a regular post
        if ($this->isRepost) {
            $this->post = Repost::find($post->id);

            $this->comments = Comment::where('repost_id', $post->id)->get();

            $this->post->commentsCount = Comment::where('repost_id', $post->id)->count();
        }
        
        $this->post->repostsCount = Repost::where('post_id', $post->id)->count();

        $this->isSummary = $isSummary;

        $this->reacted = $post->isReactedBy(auth()->user(), 'love');

        // BUG: The follow and unfollow is delayed. (FIXED)
        $this->post->user->followed = auth()->user()->isFollowing($post->user->id);
    }

    #[On('refresh-component')] 
    public function refreshComponent($data){
        //dd('Success');

        //$this->render();
        // Check if the specified postId matches the current post's id or repost original post's id
        if ($this->post && ($data['postId'] == $this->post->id || ($this->post->originalPost && $data['postId'] == $this->post->originalPost->id))) {
            // Refresh the specific post data
            if ($this->post instanceof \App\Models\Post) {
                $this->post = Post::with('reactions')->find($this->post->id);
                $this->comments = Comment::where('post_id', $this->post->id)->get();
                $this->post->commentsCount = Comment::where('post_id', $this->post->id)->count();
                $this->post->reactionsCount = $this->post->reactions->count();
            } elseif ($this->post instanceof \App\Models\Repost) {
                $this->post = Repost::with('reactions')->find($this->post->id);
                $this->comments = Comment::where('repost_id', $this->post->id)->get();
                $this->post->commentsCount = Comment::where('repost_id', $this->post->id)->count();
                $this->post->reactionsCount = $this->post->reactions->count();
            }
            
            $this->post->repostsCount = Repost::where('post_id', $this->post->id)->count();

            // BUG: The follow and unfollow is delayed. (FIXED)
            $this->post->user->followed = auth()->user()->isFollowing($this->post->user->id);
            
        }

        $this->render();
    }

    public function render()
    {
        $this->reactionCount = $this->post->reactions->count();
        $this->commentsCount = $this->post->comments->count();
        $this->repostsCount = $this->post->repostsCount;

        $postWithCommentsAndReplies = Post::where('id', $this->post->id)
        ->where('status', 'publish') // Exclude soft-deleted posts
        // ->with([
        //     'comments' => function ($query) {
        //         if ($this->isSummary) {
        //             // When isSummary is true, load only 1 comment and 1 reply
        //             $query->latest()->take(1)->with([
        //                 'replies' => function ($subQuery) {
        //                     $subQuery->latest()->take(1);
        //                 }
        //             ]);
        //         } else {
        //             // When isSummary is false, load all comments and replies
        //             $query->with('replies');
        //         }
        //     },
        //     'comments.user', // Load user information for comments
        //     'comments.reactions', // Load reactions for comments
        // ])
        ->first();

        return view('livewire.posts.single', [
            'post' => $postWithCommentsAndReplies, // Pass the modified post to the view
        ]);
    }

    protected function notifyUser(User $sender, User $receiver, Post $post)
    {
        // 5 = {Username} reacted to your post.
        $notificationData = [
            'portal_notification_id' => 5,
            'sender_id' => $sender->id,
            'user_id' => $receiver->id,
            'type' => 'react_post',
            'related_id' => $post->id,
        ];

        UserPortalNotification::create($notificationData);
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

            if (Gate::allows('react-post', [Post::class, $this->post])) {
                // Update post count
                $this->post->commentsCount = Comment::where('post_id', $this->post->id)->count();
                $this->post->repostsCount = Repost::where('post_id', $this->post->id)->count();

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

                     // Notify user about the reaction you sent.
                     $user = auth()->user();
 
                     if($this->post->user_id != $user->id){
                         $this->notifyUser($user, $this->post->user, $this->post);
                     }
                     
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

    public function reactToComment($commentId)
    {
        if (auth()->check()) {
            // Check if the comment exists
            $comment = Comment::find($commentId);
            if (!$comment) {
                $this->dispatch('show-toast', [
                    'title' => 'Error',
                    'message' => 'The content no longer exists or may have been removed.',
                    'type' => 'danger',
                    'sound' => null
                ]);
                return;
            }

            if (Gate::allows('react-post', [Post::class, $this->post])) {

                // Update post count
                $this->post->commentsCount = Comment::where('post_id', $this->post->id)->count();

                // Check if the user has already reacted to the comment
                if ($comment->isReactedBy(auth()->user(), 'love')) {
                    // Unreact to the comment
                    $comment->unreact('love', auth()->user());

                    // Update the reaction status and count
                    $this->reactedComments[$commentId] = false;
                    $this->reactionCounts[$commentId] = $comment->reactions->where('type', 'love')->count();

                    // Dispatch an event for unreacting
                    $this->dispatch('show-toast', [
                        'title' => 'Reaction Removed',
                        'message' => null,
                        'type' => 'success',
                        'sound' => null
                    ]);

                } else {
                    // React to the comment
                    $comment->react('love', auth()->user());

                    // Update the reaction status and count
                    $this->reactedComments[$commentId] = true;
                    $this->reactionCounts[$commentId] = $comment->reactions->where('type', 'love')->count();

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

    public function reactToReply($replyId)
    {
        if (auth()->check()) {
            // Check if the reply exists
            $reply = Reply::find($replyId);
            if (!$reply) {
                $this->dispatch('show-toast', [
                    'title' => 'Error',
                    'message' => 'The content no longer exists or may have been removed.',
                    'type' => 'danger',
                    'sound' => null
                ]);
                return;
            }

            if (Gate::allows('react-post', [auth()->user()])) {

                // Check if the user has already reacted to the reply
                if ($reply->isReactedBy(auth()->user(), 'love')) {
                    // Unreact to the reply
                    $reply->unreact('love', auth()->user());

                    // Dispatch an event for unreacting
                    $this->dispatch('show-toast', [
                        'title' => 'Reaction Removed',
                        'message' => null,
                        'type' => 'success',
                        'sound' => null
                    ]);
                } else {
                    // React to the reply
                    $reply->react('love', auth()->user());

                    // Dispatch an event for reacting
                    $this->dispatch('show-toast', [
                        'title' => 'Reaction Added',
                        'message' => null,
                        'type' => 'success',
                        'sound' => 'positive-btn.mp3'
                    ]);
                }
            } else {
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
                'message' => 'You must be logged in to react to a reply.',
                'type' => 'danger',
                'sound' => null
            ]);
        }
    }

    public function copyPostLinkToClipboard()
    {
        $postLink = config('app.url') . '/profile/' . $this->post->user->username . '/posts/' . $this->post->id; // Replace with your logic to get the post link
        
        // Send it to the user
        $this->dispatch('copy-post-link', ['postLink' => $postLink]);

        // Return a message
        $this->dispatch('show-toast', [
            'title' => 'Link Copied',
            'message' => 'Link copied successfully',
            'type' => 'success',
            'sound' => null
        ]);
        
    }

    public function toggleCommentStatus()
    {
        $this->post->disable_comment = !$this->post->disable_comment;
        $this->post->save();
    }

    public function openModal($type, $postId)
    {
        // Update data being removed when modal is opened
        
        $this->post->commentsCount = Comment::where('post_id', $postId)->count();
        $this->post->repostsCount = Repost::where('post_id', $postId)->count();

        switch ($type) {
            case 'views':
                $this->dispatch('model-opened', ['postId' => $postId])->to(ViewComponent::class);
                break;

            case 'reply':
                $this->dispatch('model-opened', ['postId' => $postId])->to(ReplyComponent::class);
                break;
            
            case 'repost':
                if(!empty($postId)){
                    $this->dispatch('model-opened', ['postId' => $postId])->to(RepostComponent::class);
                } else {
                    $this->dispatch('show-toast', [
                        'title' => 'Error',
                        'message' => 'Unable to repost an unavailable post.',
                        'type' => 'danger',
                        'sound' => null
                    ]);
                }
                break;
        
            // Add more cases as needed
        
            default:
                // Handle the default case, if needed
                break;
        }
    }

    public function deletePost()
    {
        if (auth()->check()) {
            $post = $this->post;

            if (!$post) {
                $this->dispatch('show-toast', [
                    'title' => 'Error',
                    'message' => 'The content no longer exists or may have been removed.',
                    'type' => 'danger',
                    'sound' => null
                ]);
                return;
            }

            // Determine the type of the post (Post or Repost)
            $postType = $post instanceof \App\Models\Repost ? 'repost' : 'post';

            // Dynamically call the Gate method based on the post type
            $gateMethod = "delete-{$postType}";

            //dd($gateMethod);
            
            if (Gate::allows($gateMethod, $post)) {
                $post->update(['status' => 'deleted']);
                $post->delete(); // Delete the post

                $this->dispatch('refresh-component', ['postId' => $post])->self();

                $this->render();

                // Dispatch an event for unreacting
                $this->dispatch('show-toast', [
                    'title' => "Post removed",
                    'message' => "Post deleted successfully.",
                    'type' => 'success',
                    'sound' => null
                ]);
            } else {
                $this->dispatch('show-toast', [
                    'title' => 'Unauthorized',
                    'message' => "You are not authorized to delete post. <a href=\"#\" class=\"learn-more-link\">Learn More</a>",
                    'type' => 'danger',
                    'sound' => null,
                ]);
            }
        } else {
            $this->dispatch('show-toast', [
                'title' => 'Error',
                'message' => 'You must be logged in to perform this action.',
                'type' => 'danger',
                'sound' => null
            ]);
        }
    }

    public function followUser(){

        $this->dispatch('follow-user', [
            'follow-user' => $this->post->user
        ])->to('profile.profile-component');

        $this->dispatch('refresh-component', ['postId' => $this->post])->self();

        // Other used
        //wire:click="$dispatchTo('profile.profile-component', 'follow-user', { userId: {{ $post->user->id }} })"
    }

    public function reportPost()
    {
        if (auth()->check()) {
            $this->dispatch('show-toast', [
                'title' => 'warning',
                'message' => 'Feature not yet implemented.',
                'type' => 'info',
                'sound' => null
            ]);
        }
    }



}
