<?php

namespace App\Services;

use App\Models\Comment;

class CommentService
{
    public function store($data)
    {
        $comment = new Comment();
        $comment->content = $data['content'];
        $comment->user_id = $data['user_id'];
        $comment->article_id = $data['article_id'];
        $comment->save();

        return $comment;
    }
    public function destroy($comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'You are not authorized to delete this comment.'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
