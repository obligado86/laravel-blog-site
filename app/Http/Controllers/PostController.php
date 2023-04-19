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
            $post->isActive = true;
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

   public function featuredPost()
   {
        $posts = Post::all();
        return view('welcome')->with('posts', $posts);
   }

    // action for showing only the posts authored by the authenticated user.
    public function myPosts()
    {
        if(Auth::user()){
            $posts = Auth::user()->posts;

            return view('posts.index')->with('posts', $posts);
        } else {
            return redirect('/login');
        }
    }

    // action that will return a view showing a specific post using the the URL parameter $id to query for the database entry to be shown.
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    //action that will return view edit post form
    public function edit($id)
    {   
        if(Auth::user()){
            $post = Post::find($id);
            if(Auth::user()->id === $post->user_id) {
                return view('posts.edit', compact('post'));
            } else {
                return redirect('/posts/' . $id);
            }
        } else {
            return redirect('/login');
        }
    }


    public function update(Request $request, $id)
    {   
        $post = Post::find($id);
        if(Auth::user()->id == $post->user_id){   
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            $post->save();
        }
        return redirect('/posts');
    }

    public function archive($id)
    {
        $post = Post::find($id);
        if(Auth::user()->id == $post->user_id){
            $post->isActive = false;
        } 
        return redirect('/posts');
    }

}
