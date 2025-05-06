<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;

class ArticleService
{
    public function index($filters)
    {
        $query = Article::with(['author', 'category', 'tags']);

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['tags'])) {
            $query->whereHas('tags', function ($query) use ($filters) {
                $query->whereIn('tags.id', $filters['tags']);
            });
        }

        if (isset($filters['sort_by_date'])) {
            $query->orderBy('created_at', $filters['sort_by_date']); 
        }

        if (isset($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        $perPage = Setting::getValue('articles_per_page') ?? 10; 

        return $query->paginate($perPage);
    }
    public function store($data)
    {
        $article = new Article();
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->category_id = $data['category_id'];
        $article->user_id = auth()->id();
        $article->status = 'draft';
        if (isset($data['image'])) {
            $article->image_url = $data['image']->store('images', 'public');
        }
        if (isset($data['video'])) {
            $article->video_url = $data['video']->store('videos', 'public');
        }
        $article->save();
        if (isset($data['tags'])) {
            $tags = Tag::find($data['tags']);
            $article->tags()->sync($tags);
        }
        return $article;
    }
    public function update($data, $id)
    {
        $article = Article::findOrFail($id);
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->category_id = $data['category_id'];

        if (isset($data['image'])) {
            $article->image_url = $data['image']->store('images', 'public');
        }

        if (isset($data['video'])) {
            $article->video_url = $data['video']->store('videos', 'public');
        }

        $article->save();
        if (isset($data['tags'])) {
            $tags = Tag::find($data['tags']); 
            $article->tags()->sync($tags);  
        }
        return $article;
    }
    public function destroy($article)
    {
        if ($article->image_url) {
            Storage::disk('public/')->delete($article->image_url);
        }
        if ($article->video_url) {
            Storage::disk('public/')->delete($article->video_url);
        }
        $article->tags()->detach();
        $article->delete();
    }
}
