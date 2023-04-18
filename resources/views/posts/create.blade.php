@extends('layouts.app')


@section('content')
	<form method="POST" action="/posts">
		@csrf
		<div class="form-group">
			<label for="title">Title:</label>
			<input type="text" name="title" id="title" class="form-control">
		</div>
		<div class="form-group">
			<label for="content">Content:</label>
			<textarea class="form-control" id="content" name="content" rows="3"></textarea>
		</div>
		<div class="mt-2">
			<button type="submit" class="btn btn-primary">Create Post</button>
		</div>
	</form>
@endsection
