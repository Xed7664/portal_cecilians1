<div class="card newsfeed my-2">
    <div class="newsfeed-body">
        <div class="form-group mt-3 ms-2 mb-3">
            <textarea wire:model="postContent" class="form-control post mention-enabled" style="height: 40px; overflow-y: hidden;" spellcheck="false" placeholder="What's on your mind?"></textarea>
        </div>
        <div class="d-grid gap-2 mt-3 mb-3">
            <button wire:click="createPost" wire:loading.class="visually-hidden" wire:target="createPost" class="btn btn-portal" type="button">
                Post
            </button>

            <button wire:loading.class.remove="visually-hidden" class="btn btn-portal visually-hidden" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Posting
            </button>
        </div>
    </div>
</div>
