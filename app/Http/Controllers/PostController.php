<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function showCreateForm()
    {
        return view('create-post');
    }
    public function viewSinglePost(Post $post)
    {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>');
        $user_comments = Comment::where('post_id', $post->id)->latest()->paginate(3);
        $user = auth()->user();
        return view('single-post', ['post' => $post, 'user' => $user ,'user_comments' => $user_comments]);
    }
    public function store(PostRequest $request)
    {
        $incomingFields = $request->validated();
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);

        return redirect("/post/{$newPost->id}")->with('success', 'Post created successfully!');
    }
    public function storePostAPI(PostRequest $request)
    {
        $incomingFields = $request->validated();
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        return $newPost->id;
    }
    public function delete(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect('/')->with('success', 'Post deleted successfully!');
    }
    public function deleteAPI(Post $post)
    {
        $post->delete();
        return 'true';

    }
    public function showEditForm(Post $post)
    {
        if(!auth()->user()->can('update', $post))
        {
            return redirect()->route('post.single', $post)->with('error', 'You are not authorized to edit this post.');
        }
        return view('edit-post', ['post' => $post]);
    }
    public function update(Post $post ,PostRequest $request)
    {
        $this->authorize('update', $post);
        $current_user_id = auth()->user()->id;
        $incomingFields = $request->validated();
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['updated_by'] = $current_user_id;
        $post->update($incomingFields);
        return redirect()->route('post.single',$post)->with('success', 'Post updated successfully!');
    }
    public function search($keyword)
    {
        $posts = Post::search($keyword)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }


}

