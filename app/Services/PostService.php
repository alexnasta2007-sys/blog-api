<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    public function create(User $user, array $data): Post
    {
        return $user->posts()->create([
            'title' => $data['title'],
            'text' => $data['text'],
        ]);
    }

    public function getAll(
        int $limit = 10,
        int $offset = 0,
        ?string $sort = null,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): LengthAwarePaginator {
        $query = Post::query();

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $this->applySorting($query, $sort);

        return $query
            ->with('user')
            ->offset($offset)
            ->paginate($limit);
    }

    public function getMyPosts(
        User $user,
        int $limit = 10,
        int $offset = 0,
        ?string $sort = null,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): LengthAwarePaginator {
        $query = $user->posts();

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $this->applySorting($query, $sort);

        return $query
            ->offset($offset)
            ->paginate($limit);
    }

    private function applySorting($query, ?string $sort): void
    {
        match ($sort) {
            'title' => $query->orderBy('title', 'asc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            'date_desc', null => $query->orderBy('created_at', 'desc'),
            'date' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }
}