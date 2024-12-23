<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required'
        ]);
        $comment = new Comment();
        $comment->body = strip_tags($request->body);
        $comment->user_id = auth()->id();
        $post->comments()->save($comment);
        return redirect()->back()->with('success', 'Comment added successfully!');
    }
    public function deleteComment(Post $post,Comment $comment)
    {
        $post->comments()->where('id', $comment->id)->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
    public function updateComment(Request $request, Post $post, Comment $comment)
    {
        $comment->body = $request->input('body');
        $comment->save();
        return redirect()->back()->with('success', 'Comment updated successfully!');
    }
}
