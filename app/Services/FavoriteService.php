<?php

namespace App\Services;

use App\Enums\FavoritableType;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FavoriteService
{
    public function get(User $user) : array
    {
        $favorites = Favorite::where('user_id', $user->id)->get();
        $posts = [];
        $users = [];

        foreach ($favorites as $favorite) {
            if ($favorite->favoritable_type === FavoritableType::POST->value) {
                $posts[] = [
                    'id' => $favorite->favoritable->id,
                    'title' => $favorite->favoritable->title,
                    'body' => $favorite->favoritable->body,
                    'user' => [
                        'id' => $favorite->favoritable->user->id,
                        'name' => $favorite->favoritable->user->name,
                    ]
                ];
            } elseif ($favorite->favoritable_type === FavoritableType::USER->value) {
                $users[] = [
                    'id' => $favorite->favoritable->id,
                    'name' => $favorite->favoritable->name,
                ];
            }
        }

        return [
            'posts' => $posts,
            'users' => $users,
        ];
    }

    public function favorite(User $user, Model $model): Model
    {
        $favorite = $model->favorites()->create([
            'user_id' => $user->id,
        ]);

        return $favorite;
    }

    public function unfavorite(User $user, Model $model): void
    {
        $model->favorites()->where('user_id', $user->id)->delete();
    }

    public function isFavorited(User $user, Model $model): bool
    {
        return $model->favorites()->where('user_id', $user->id)->exists();
    }
}
