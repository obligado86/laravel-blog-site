@extends('layouts.app')


@section('content')
	<div class="card">
		<div class="card-body">
			<h2 class="card-tilte">{{$post->title}}</h2>
			<p class="card-subtitle text-muted">Author: {{$post->user->name}}</p>
			<p class="card-subtitle text-muted mb-3">Created at {{$post->created_at}}</p>
			<img src="{{$post->image}}" width="400px" />
			<p class="card-text">{{$post->content}}</p>
			<p class="card-text text-muted">Likes: {{count($likes)}} | Comments: {{count($comments)}}</p>

			@if(Auth::id() != $post->user_id)
				<form class="d-inline" method="POST" action="/posts/{{$post->id}}/like">
					
					@method('PUT')
					@csrf

					@if($post->likes->contains("user_id", Auth::id()))
						<button type="submit" class="btn btn-danger">Unlike <span class="fa fa-heart-o"></button>
						<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#comment-modal">Comment <span class="fa fa-pencil"></span></button>
					@else
						<button type="submit" class="btn btn-primary">Like <span class="fa fa-heart-o"></span></button>
						<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#comment-modal">Comment <span class="fa fa-pencil"></span></button>
					@endif
				</form>
			@else
				<form method="POST" action="/posts/{{$post->id}}/delete">
					@method('PUT')
					@csrf
					<a href="/posts/{{$post->id}}/edit" class="btn btn-primary">Edit Post <span class="fa fa-edit"></span></a>
					<button type="submit" class="btn btn-danger">Delete Post <span class="fa fa-trash"></span></button>
					<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#comment-modal">Comment <span class="fa fa-pencil"></span></button>
				</form>
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
	<h3 class="mt-5">Comments:</h3>
	<div>
		@foreach($comments as $comment)
		<div class="card text-left">
			<div class="card-body">
				<h6 class="card-text mb-3">Commented by: {{$comment->user->name}}</h6>	
				<p class="card-subtitle mb-3 text-muted">Created at: {{$comment->created_at}}</p>
				<p class="card-subtitle mb-3">{{$comment->content}}</p>
				@if(Auth::user())
					@if(Auth::user()->id == $post->user->id)
						<div class="card-footer">
							<form method="POST" action="/posts/{{$post->id}}/delete">
								@method('PUT')
								@csrf
								<a href="/posts/{{$post->id}}/edit" class="btn btn-primary"><span class="text-light fa fa-edit"></span></a>
								<button type="submit" class="btn btn-danger"><span class="text-light fa fa-trash"></span></button>
							</form>
						</div>
					@endif
				@endif
			</div>
		</div>
	@endforeach
	</div>
@endsection
