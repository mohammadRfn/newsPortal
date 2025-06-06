<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiArticleRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }
    public function index(Request $request)
    {
        $filters = $request->only([
            'category_id',
            'tags',
            'sort_by_date',
            'search',
            'per_page'
        ]);

        $articles = $this->articleService->index($filters);
        return response()->json($articles);
    }
    public function store(ApiArticleRequest $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403); 
        }
        $data = $request->only(['title', 'content', 'category_id']);
        $data['status'] = 'draft';
        $data['user_id'] = auth()->id();
        $data['image'] = $request->file('image');
        $data['video'] = $request->file('video');
        $data['tags'] = $request->tags;

        $article = $this->articleService->store($data);

        return response()->json($article, 201);
    }
    public function update(ArticleUpdateRequest $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);  
        }

        $article = Article::findOrFail($id);

        $data = $request->only(['title', 'content', 'category_id']);
        $data['image'] = $request->file('image');
        $data['video'] = $request->file('video');
        $data['tags'] = $request->tags;

        $updatedArticle = $this->articleService->update($data, $id);

        return response()->json($updatedArticle);
    }
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);  
        }
        $article = Article::findOrFail($id);
        $this->articleService->destroy($article);

        return response()->json(['message' => 'Article deleted successfully'], 200);
    }
}
