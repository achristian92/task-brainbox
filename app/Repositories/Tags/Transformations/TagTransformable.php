<?php


namespace App\Repositories\Tags\Transformations;


use App\Repositories\Tags\Tag;

trait TagTransformable
{
    public function transformToList(Tag $tag)
    {
        return [
            'id'     => $tag->id,
            'name'   => $tag->name,
            'status' => $tag->status,
            'color'  => $tag->color
        ];
    }

}
