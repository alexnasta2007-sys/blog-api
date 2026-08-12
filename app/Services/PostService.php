<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

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

        $this->applyDateFilter($query, $dateFrom, $dateTo);
        $this->applySorting($query, $sort);

        return $query
            ->with('user')
            ->paginate(
                perPage: $limit,
                page: $this->calculatePage($offset, $limit)
            );
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

        $this->applyDateFilter($query, $dateFrom, $dateTo);
        $this->applySorting($query, $sort);

        return $query->paginate(
            perPage: $limit,
            page: $this->calculatePage($offset, $limit)
        );
    }

    private function applyDateFilter(
        Builder $query,
        ?string $dateFrom,
        ?string $dateTo
    ): void {
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
    }

    private function applySorting(
        Builder $query,
        ?string $sort
    ): void {
        match ($sort) {
            'title' => $query->orderBy('title', 'asc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            'date' => $query->orderBy('created_at', 'asc'),
            'date_desc', null => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }

    private function calculatePage(int $offset, int $limit): int
    {
        return (int) floor($offset / $limit) + 1;
    }
}