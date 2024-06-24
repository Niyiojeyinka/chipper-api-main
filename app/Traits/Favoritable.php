<?php
namespace App\Traits;

use App\Enums\FavoritableType;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Favoritable
{
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
