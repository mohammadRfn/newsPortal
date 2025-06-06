<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    public function store(ApiCommentRequest $request, $articleId)
    {
        $data = $request->only(['content']);
        $data['user_id'] = auth()->id();
        $data['article_id'] = $articleId;
        
        if ($request->has('parent_id')) {
            $data['parent_id'] = $request->parent_id;
        }

        $comment = $this->commentService->store($data);

        return response()->json($comment, 201);
    }
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        return $this->commentService->destroy($comment);
    }
}
