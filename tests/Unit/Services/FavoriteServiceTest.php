<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Post;
use App\Services\FavoriteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteServiceTest extends TestCase
{
    use RefreshDatabase;

    protected FavoriteService $favoriteService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->favoriteService = app(FavoriteService::class);
    }

    public function test_favorite_creates_favorite_record()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $favorite = $this->favoriteService->favorite($user, $post);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_id' => $post->id,
            'favoritable_type' => get_class($post),
        ]);

        $this->assertEquals($user->id, $favorite->user_id);
        $this->assertEquals($post->id, $favorite->favoritable_id);
    }

    public function test_unfavorite_deletes_favorite_record()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->favoriteService->favorite($user, $post);
        $this->favoriteService->unfavorite($user, $post);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'favoritable_id' => $post->id,
            'favoritable_type' => get_class($post),
        ]);
    }

    public function test_is_favorited_returns_true_if_favorited()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->favoriteService->favorite($user, $post);

        $isFavorited = $this->favoriteService->isFavorited($user, $post);

        $this->assertTrue($isFavorited);
    }

    public function test_is_favorited_returns_false_if_not_favorited()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $isFavorited = $this->favoriteService->isFavorited($user, $post);

        $this->assertFalse($isFavorited);
    }
}
