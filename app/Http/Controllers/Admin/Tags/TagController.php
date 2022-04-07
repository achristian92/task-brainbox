<?php


namespace App\Http\Controllers\Admin\Tags;

use App\Http\Controllers\Controller;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tags\Tag;
use App\Repositories\Tags\Transformations\TagTransformable;

class TagController extends Controller
{
    use TagTransformable;

    /**
     * @var ITag
     */
    private $tagRepo;

    public function __construct(ITag $ITag)
    {
        $this->tagRepo = $ITag;
    }

    public function __invoke()
    {
        $tags = $this->tagRepo->listTags()
            ->transform(function ($tag) {
                return $this->transformToList($tag);
            });

        return view('admin.tags.index',compact('tags'));
    }
}
