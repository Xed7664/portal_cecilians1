@extends('posts.layout')

@section('title', 'Newsfeed')

@section('content_post')
@inject('postController', 'App\Http\Controllers\PostController')

    @livewire('posts.create-post')

    @livewire('posts.post-component')

@endsection
