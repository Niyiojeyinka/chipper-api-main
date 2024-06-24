<?php
namespace App\Enums;

enum FavoritableType: string
{
    case USER = 'App\Models\User';
    case POST = 'App\Models\Post';

    public static function value(string $name): string
    {
        return match ($name) {
            self::USER->name => self::USER->value,
            self::POST->name => self::POST->value,
        };
    }

    public static function names(): array
    {
        return [
            self::USER->name,
            self::POST->name,
        ];
    }

}
