@extends('layout.template')
@section('title', 'Input Data Movie')
@section('content')
		<h2 class="mb-4">Edit Movie</h2>
        <form action="{{ route('movies.update', ['movie' => $movie->id]) }}" method="POST" enctype="multipart/form-data">
			@csrf
			@include('partials.movie-form')
		</form>
		@endsection
