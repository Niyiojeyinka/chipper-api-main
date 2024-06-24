<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_guest_can_not_favorite_a_post()
    {
        $post = Post::factory()->create();

        $this->postJson(route('favorites.post', ['post' => $post]))
            ->assertStatus(401);
    }

    public function test_a_user_can_favorite_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->postJson(route('favorites.post', ['post' => $post]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'favoritable_id' => $post->id,
            'user_id' => $user->id,
            'favoritable_type' => Post::class,
        ]);
    }

    public function test_a_user_can_remove_a_post_from_his_favorites()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->postJson(route('unfavorites.post', ['post' => $post]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'favoritable_id' => $post->id,
            'user_id' => $user->id,
            'favoritable_type' => Post::class,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('unfavorites.post', ['post' => $post]))
            ->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'favoritable_id' => $post->id,
            'user_id' => $user->id,
            'favoritable_type' => Post::class,
        ]);
    }

    public function test_a_user_can_not_remove_a_non_favorited_item()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('unfavorites.post', ['post' => $post]))
            ->assertNotFound();
    }

    public function test_a_user_can_favorite_another_user()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('favorites.user', ['user' => $anotherUser]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'favoritable_id' => $anotherUser->id,
            'user_id' => $user->id,
            'favoritable_type' => User::class,
        ]);
    }

    public function test_a_user_can_remove_another_user_from_his_favorites()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('favorites.user', ['user' => $anotherUser]))
            ->assertCreated();

        $this->assertDatabaseHas('favorites', [
            'favoritable_id' => $anotherUser->id,
            'user_id' => $user->id,
            'favoritable_type' => User::class,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('unfavorites.user', ['user' => $anotherUser]))
            ->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'favoritable_id' => $anotherUser->id,
            'user_id' => $user->id,
            'favoritable_type' => User::class,
        ]);
    }

    public function test_a_user_can_not_remove_a_non_favorited_user()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('unfavorites.user', ['user' => $anotherUser]))
            ->assertNotFound();
    }

}
