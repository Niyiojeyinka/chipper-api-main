<?php
namespace App\Traits;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Favoritable
{
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
