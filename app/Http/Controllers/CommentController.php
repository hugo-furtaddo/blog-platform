<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific post.
     */
    public function index($postId)
    {
        $post = Post::findOrFail($postId);
        $comments = $post->comments()->with('user')->get();

        return response()->json($comments, 200);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $this->validate($request, [
            'content' => 'required|string|max:500',
        ]);

        $comment = new Comment;
        $comment->content = $request->content;
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->save();

        return response()->json($comment, 201);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($postId, $commentId)
    {
        $post = Post::findOrFail($postId);
        $comment = $post->comments()->where('id', $commentId)->firstOrFail();

        // Verifica se o usuário é dono do comentário ou administrador
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
