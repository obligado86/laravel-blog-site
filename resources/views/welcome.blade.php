@extends('layouts.app')

@section('content')
<div class="flex text-center sm:pt-0">
    <img src="https://cdn.freebiesupply.com/logos/large/2x/laravel-1-logo-png-transparent.png" alt="logo" width="300px">
</div>
<div class="container">
    <h2>Featured Post:</h2>
    @if(count($posts) > 0)
        @foreach($posts->random(min(3,count($posts))) as $post)
        <div class="card text-center mb-3">
            <div class="card-body">
                <h4 class="card-title mb-3"><a href="/posts/{{$post->id}}">{{$post->title}}</a></h4>
                <h6 class="card-text mb-3">author: {{$post->user->name}}</h6>
            </div>
        </div>
        @endforeach
    @else
        <div>
            <h2>There are no post to show at this moment</h2>
            <a href="/posts/create" class="btn btn-info">Create Post</a>
        </div>
    @endif
</div>

@endsection