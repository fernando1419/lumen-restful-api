<?php

namespace App\ApiProject\Transformers;

abstract class Transformer
{
    /**
     * transformCollection of $items
     *
     * @param mixed $items
     * @return void
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * transform an $item. This method MUST BE implemented in the subclasses.
     *
     * @param mixed $item
     * @return void
     */
    public abstract function transform($item);

}
