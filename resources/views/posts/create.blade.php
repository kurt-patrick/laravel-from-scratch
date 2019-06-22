@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <!-- NOTE: name is required for the POST, the value will not be posted without it -->
            <input id="title" name="title" type="text" class="form-control" placeholder="Title" />
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <!-- NOTE: name is required for the POST, the value will not be posted without it -->
            <textarea id="article-ckeditor" name="body" type="text" class="form-control" placeholder="Body Text"></textarea>
        </div>
        <div class="form-group">
            <input type="file" name="cover_image">
        </div>
        <input type="submit" class="btn btn-primary" value="Submit">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
