<?php

namespace App\Http\Controllers\Front\Tags;

use App\Http\Controllers\Controller;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tags\Requests\StoreTagFormRequest;
use App\Repositories\Tags\Requests\UpdateTagFormRequest;
use App\Repositories\Tags\Transformations\TagTransformable;
use App\Repositories\UsersHistories\UserHistory;

class TagController extends Controller
{
    use TagTransformable;

    private $tagRepo;

    public function __construct(ITag  $ITag)
    {
        $this->tagRepo = $ITag;
    }

    public function index()
    {
        $transformToList = $this->tagRepo->listTags()
                            ->transform(function ($tag) {
                                return $this->transformToList($tag);
                            });
        return response()->json([
            'tags' => $transformToList,
        ]);
    }

    public function store(StoreTagFormRequest $request)
    {
        $request->merge(['status' => true]);
        $tag = $this->tagRepo->createTag($request->only('name','color','status'));

        _addHistory(UserHistory::STORE,"Creó la etiqueta $tag->name",$tag);

        return response()->json([
            'tag' => $this->transformToList($tag),
            'route' => route('admin.tags.index'),
            'msg' => 'Etiqueta creada'
        ],201);
    }

    public function edit($id)
    {
        return response()->json([
            'tag' => $this->transformToList($this->tagRepo->findTagById($id))
        ]);
    }

    public function update(UpdateTagFormRequest $request, int $id)
    {
        $this->tagRepo->updateTag($request->only(['name','color']),$id);

        $tag = $this->tagRepo->findTagById($id);
        _addHistory(UserHistory::UPDATED,"Actualizó la etiqueta $tag->name",$tag);

        return response()->json([
            'tag' => $this->transformToList($tag),
            'msg' => 'Etiqueta actualizada',
        ]);
    }

    public function destroy($id)
    {
        $message = "Etiqueta eliminada";
        $message = $this->tagRepo->deleteTag($id) ? $message : 'Etiqueta desactivada';

        return response()->json([
            'msg' => $message,
        ]);
    }
}
