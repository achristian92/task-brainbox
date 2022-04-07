<?php


namespace App\Repositories\Tags\Repository;


use App\Repositories\Tags\Tag;
use Illuminate\Database\Eloquent\Collection;

interface ITag
{
    public function findTagById(int $tag_id): Tag;

    public function listTags(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function listTagsActived(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function createTag(array $params): Tag;

    public function updateTag(array $params, int $tag_id): bool ;

    public function deleteTag(int $tag_id): bool;

    public function listTagsByIds(array $ids, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']): Collection;


}
