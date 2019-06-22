<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

// to see routes: php artisan route:list
class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // middleware provides security by disabling all pages unless the user is logged in
        // the except statement below means block all pages except index and show.
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //use dd() for debugging the data coming in on the request
        //dd($request->all());

        // cover_image must be of type image and is optional
        // max:1999 refers to file size inidcating it has to be just under 2mb
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // handle file upload
        $filenameToStore = 'noimage.jpg';
        if($request->hasFile('cover_image')) {
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $file_name = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $file_ext = $request->file('cover_image')->getClientOriginalExtension();
            $file_ext = pathinfo($filenameWithExt, PATHINFO_EXTENSION);
            $filenameToStore = $file_name . time() . ".$file_ext";

            // the file will be stored in /storage/app/public/cover_images
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);

            // in the command line run the command
            // php artisan storage:link
            // this will create a sim link of the file in /storage/app/public into into the /public/storage folder
        }

        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $filenameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        // check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorised Page');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //use dd() for debugging the data coming in on the request
        //dd($request->all());

        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // handle file upload
        $filenameToStore = 'noimage.jpg';
        if($request->hasFile('cover_image')) {
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $file_name = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $file_ext = $request->file('cover_image')->getClientOriginalExtension();
            $file_ext = pathinfo($filenameWithExt, PATHINFO_EXTENSION);
            $filenameToStore = $file_name . time() . ".$file_ext";

            // the file will be stored in /storage/app/public/cover_images
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);

            // in the command line run the command
            // php artisan storage:link
            // this will create a sim link of the file in /storage/app/public into into the /public/storage folder
        }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')) {
            $post->cover_image = $filenameToStore;
        }

        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        // check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorised Page');
        }

        // delete the associated image
        if($post->cover_image != 'noimage.jpg'){
            Storage::delete("public/cover_images/$post->cover_image");
        }

        $post->delete();
        return redirect('/posts')->with('success', 'Post Deleted');    }
}
