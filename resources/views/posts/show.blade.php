@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-default">Go back</a>
    <h1>{{$post->title}}</h1>
    <img style="width: 100%" src="/storage/cover_images/{{$post->cover_image}}">
    <br><br>
    <div>
        <!-- NOTE: By using double !! the body will be rendered as html -->
        {!!$post->body!!}
    </div>
    <hr/>
    <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
    <hr>

    <!-- only show edit and delte if the user is logged in -->
    <!-- (AND) the post is associated is the logged in user -->
    @if(!Auth::guest())
        @if(Auth::user()->id == $post->user_id)
            <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>

            <form action="{{ route('posts.destroy', ['id' => $post->id]) }}" method="POST">
                <input type="submit" class="btn btn-danger" value="Delete">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        @endif
    @endif

@endsection
