<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testCanCreateAnArticle()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => '1234',
        ]);
        $token = $response->json('token');

        $category = Category::first();
        $user = User::first(); 

        $articleData = [
            'title' => 'Test for article',
            'content' => 'This is a test article content',
            'category_id' => $category->id,  
            'user_id' => $user->id,  
        ];

        $response = $this->postJson('/api/articles', $articleData, [
            'Authorization' => "Bearer $token", 
        ]);

        $response->assertStatus(201); 

        $this->assertDatabaseHas('articles', [
            'title' => $articleData['title'],
            'content' => $articleData['content'],
        ]);
    }
}
