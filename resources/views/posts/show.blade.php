@extends('layouts.app')


@section('content')
	<div class="card">
		<div class="card-body">
			<h2 class="card-tilte">{{$post->title}}</h2>
			<p class="card-subtitle text-muted">Author: {{$post->user->name}}</p>
			<p class="card-subtitle text-muted mb-3">Created at {{$post->created_at}}</p>
			<img src="{{$post->image}}" width="400px" />
			<p class="card-text">{{$post->content}}</p>

			@if(Auth::id() != $post->user_id)
				<form class="d-inline" method="POST" action="/posts/{{$post->id}}/like">
					
					@method('PUT')
					@csrf

					@if($post->likes->contains("user_id", Auth::id()))
						<button type="submit" class="btn btn-danger">Unlike</button>
					@else
						<button type="submit" class="btn btn-success">Like</button>
					@endif
				</form>
			@endif

			@if(Auth::user())
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#comment-modal">Comment</button>
			@endif

			<div class="modal fade" id="comment-modal" tabindex="-1">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<h5 class="modal-title" id="exampleModalLabel">Comments</h5>
			        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			      		</div>
			      		<div class="modal-body">
			        		<form method="POST" action="/posts/{{$post->id}}/comment">
			        			@method('POST')
			        			@csrf
			          			<div class="mb-3">
			            			<label for="content" class="col-form-label">Comment:</label>
			            			<textarea class="form-control" id="content" name="content" rows="3"></textarea>
			          			</div>
			          			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			        			<button type="submit" class="btn btn-primary">Post</button>
			        		</form>
			      		</div>
			    	</div>
			  	</div>
			</div>


			<div class="mt-3">
				<a href="/posts" class="card-link">View all posts</a>	
			</div>
		</div>
	</div>
	
@endsection
