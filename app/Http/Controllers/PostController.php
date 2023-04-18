<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// access the authenticated user via Auth Facade
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    
    public function create()
    {
        return view('posts.create');
    }


    public function store(Request $request)
    {
        if(Auth::user()) {
            // instantiate a new Post object from the Post Model
            $post = new Post;
            // define the properties of the $post object using the received form data.
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            // get the id of the authenticated user and set it as the foreign key for the user_id of the new post.
            $post->user_id = (Auth::user()->id);
            // save this $post object into the database.
            $post->save();

            return redirect('/posts');
        } else {
            return redirect('/login');
        }
    }

    // action that will return a view showing all the blog posts.
    public function index()
    {
        $posts = Post::all();
        return view('posts.index')->with('posts', $posts);
    }



}
