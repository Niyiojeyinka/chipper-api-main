<?php

namespace App\Models;

use App\Notifications\NewPostNotification;
use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Notification;

class Post extends Model
{
    use HasFactory, Favoritable;

    protected $fillable = ['title', 'body', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($post) {
            $favoredUsers = $post->user->favorites()
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id')
            ->all();

            Notification::send($favoredUsers, new NewPostNotification($post));
        });
    }
}
