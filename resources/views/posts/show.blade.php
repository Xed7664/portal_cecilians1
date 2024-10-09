@extends('posts.layout')

@section('title', 'Newsfeed')

@section('content_post')
@inject('postController', 'App\Http\Controllers\PostController')

<div class="card newsfeed">
    @livewire('posts.single-full', ['post' => $post, 'isSummary' => true], key($post->id))
</div>

@endsection
