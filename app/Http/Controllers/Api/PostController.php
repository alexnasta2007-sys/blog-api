<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostIndexRequest;
use App\Http\Requests\StorePostRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService
    ) {
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->postService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Публикация успешно создана.',
            'post' => $post,
        ], 201);
    }

    public function index(PostIndexRequest $request): JsonResponse
    {
        $data = $request->validated();

        $posts = $this->postService->getAll(
            $data['limit'] ?? 10,
            $data['offset'] ?? 0,
            $data['sort'] ?? null,
            $data['date_from'] ?? null,
            $data['date_to'] ?? null,
        );

        return response()->json($posts);
    }

    public function myPosts(PostIndexRequest $request): JsonResponse
    {
        $data = $request->validated();

        $posts = $this->postService->getMyPosts(
            $request->user(),
            $data['limit'] ?? 10,
            $data['offset'] ?? 0,
            $data['sort'] ?? null,
            $data['date_from'] ?? null,
            $data['date_to'] ?? null,
        );

        return response()->json($posts);
    }
}