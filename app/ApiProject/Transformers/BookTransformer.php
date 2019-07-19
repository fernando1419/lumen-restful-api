<?php

namespace App\ApiProject\Transformers;

use Carbon\Carbon;

class BookTransformer extends Transformer
{
    /**
     * transform
     *
     * @param mixed $book
     * @return array
     */
    public function transform($book)
    {
        return [
            'title'       => $book['title'],
            'description' => $book['description'],
            'isbn'        => $book['isbn'],
            'author_id'   => $book['author_id'],
            'updated'     => Carbon::parse($book['updated_at'])->toIso8601String(),
            'released'    => Carbon::parse($book['created_at'])->diffForHumans()
        ];
    }
}
