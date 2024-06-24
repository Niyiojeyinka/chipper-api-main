<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FavoriteService
{
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
