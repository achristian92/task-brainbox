<?php


namespace App\Repositories\Tags\Repository;


use App\Repositories\Tags\Tag;
use App\Repositories\UsersHistories\UserHistory;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class TagRepo extends BaseRepository implements ITag
{

    public function model()
    {
        return Tag::class;
    }

    public function findTagById(int $tag_id): Tag
    {
        return $this->model->findOrFail($tag_id);
    }

    public function listTags(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model->orderBy($orderBy,$sortBy)->get($columns);
    }

    public function listTagsActived(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model->where('status',true)->orderBy($orderBy,$sortBy)->get($columns);
    }

    public function createTag(array $params): Tag
    {
        return $this->model->create($params);
    }

       public function updateTag(array $params, int $tag_id): bool
    {
        $tag = $this->findTagById($tag_id);
        return $tag->update($params);
    }

    private function deactivate(int $id): void
    {
        $tag = $this->findTagById($id);
        $tag->status = false;
        $tag->save();
    }

    public function deleteTag(int $tag_id): bool
    {
        $isDelete = true;
        $tag = $this->findTagById($tag_id);
        if ($tag->activities()->exists()) {
            $tag->update(['status' => false]);
            $isDelete = false;
            _addHistory(UserHistory::DISABLE,"Desabilitó la etiqueta $tag->name",$tag);
        } else {
            _addHistory(UserHistory::DELETE,"Eliminó la etiqueta $tag->name",$tag);
            $tag->delete();
        }

        return $isDelete;
    }


    public function listTagsByIds(array $ids, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']): Collection
    {
        return $this->model::whereIn('id',$ids)->orderBy($orderBy,$sortBy)->get($columns);
    }
}
