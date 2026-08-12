<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $post = $this->postService->create(
            $request->user(),
            $data
        );

        return response()->json([
            'message' => 'Публикация успешно создана.',
            'post' => $post,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'sort' => ['nullable', 'in:date,date_desc,title,title_desc'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $posts = $this->postService->getAll(
            $data['limit'] ?? 10,
            $data['offset'] ?? 0,
            $data['sort'] ?? null,
            $data['date_from'] ?? null,
            $data['date_to'] ?? null,
        );

        return response()->json($posts);
    }

    public function myPosts(Request $request): JsonResponse
    {
        $data = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'sort' => ['nullable', 'in:date,date_desc,title,title_desc'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

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
