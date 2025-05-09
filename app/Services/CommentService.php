<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function store($data)
    {
        if (isset($data['parent_id'])) {
            $parentComment = Comment::find($data['parent_id']);

            if (!$parentComment) {
                return response()->json(['error' => 'Parent comment not found.'], 404);
            }
        }

        if (!isset($data['parent_id'])) {
            $data['parent_id'] = null;
        }
        $comment = new Comment();
        $comment->content = $data['content'];
        $comment->user_id = $data['user_id'];
        $comment->article_id = $data['article_id'];
        $comment->parent_id = $data['parent_id'];
        
        $comment->save();

        return $comment;
    }
    public function destroy($comment)
    {
        if ($comment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You are not authorized to delete this comment.'], 403);
        }
        $comment->replies()->delete();
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
