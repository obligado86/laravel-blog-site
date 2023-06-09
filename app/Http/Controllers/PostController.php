<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// access the authenticated user via Auth Facade
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;

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
            $post->image = $request->input('image');
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

    /*public function index()
    {
        $posts = Post::where('is_active', true)->get();
        return view('posts.index')->with('posts', $posts);
    }*/

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
        $comments = PostComment::where('post_id', $id)->get();
        $likes = PostLike::where('post_id', $id)->get();
        return view('posts.show', compact('post', 'comments', 'likes'));
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
            $post->save();
        } 
        return redirect('/posts');
    }

    public function unarchive($id)
    {
        if(Auth::user()->id == $post->user_id){
            $post->isActive = true;
            $post->save();
        } 
        return redirect('/posts');
    }

    public function like($id)
    {
        $post = Post::find($id);
        $user_id = Auth::user()->id;
        if($post->user_id != $user_id){
            // checks if a post like has been made by the login user
            if($post->likes->contains('user_id', $user_id)){
                // delete the like made by the user to unlike the post.
                PostLike::where('post_id', $post->id)->where('user_id', $user_id)->delete();
            } else {
                $postLike = new PostLike;
                $postLike->post_id = $post->id;
                $postLike->user_id = $user_id;
                $postLike->save();
            }
            return redirect("/posts/$id");
        }
    }

    public function comment(Request $request, $id)
    {
        $post = Post::find($id);
        $user_id = Auth::user()->id;
        if(Auth::user()){
            $comment = new PostComment;
            $comment->post_id = $post->id;
            $comment->user_id = $user_id;
            $comment->content = $request->input('content');
            $comment->save();
            return redirect("/posts/$id");
        } else {
            return redirect("/login");
        }
        
    }

    /*public function showComment($id)
    {
        $comments = PostComment::all();
        return view('posts.comments')->with('comments', $comments);
    }*/

}
