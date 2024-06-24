<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\CreateFavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\User;
use App\Services\FavoriteService;
use Illuminate\Http\Response;

/**
 * @group Favorites
 *
 * API endpoints for managing favorites
 */
class FavoriteController extends Controller
{
    private FavoriteService $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function index(Request $request)
    {
        $favorites = $request->user()->favorites;
        return FavoriteResource::collection($favorites);
    }

    public function favoritePost(CreateFavoriteRequest $request, Post $post)
    {
        $this->favoriteService->favorite($request->user(), $post);

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function unfavoritePost(Request $request, Post $post)
    {
        if (!$this->favoriteService->isFavorited($request->user(), $post)) {
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }

        $this->favoriteService->unfavorite($request->user(), $post);

        return response()->noContent();
    }

    public function favoriteUser(CreateFavoriteRequest $request, User $user)
    {
        $this->favoriteService->favorite($request->user(), $user);

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function unfavoriteUser(Request $request, User $user)
    {
        if (!$this->favoriteService->isFavorited($request->user(), $user)) {
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }

        $this->favoriteService->unfavorite($request->user(), $user);

        return response()->noContent();
    }
}
