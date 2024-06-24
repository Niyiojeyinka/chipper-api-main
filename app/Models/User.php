<?php

namespace App\Models;

use App\Enums\FavoritableType;
use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Favoritable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'user_id', 'favoritable_id')
                    ->wherePivot('favoritable_type', FavoritableType::USER->value);
    }

    public function favoritePosts()
    {
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'favoritable_id')
                    ->wherePivot('favoritable_type', FavoritableType::POST->value);
    }
}
