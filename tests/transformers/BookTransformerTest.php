<?php

namespace tests\transformers;

use App\Book;

use TestCase;
use App\ApiProject\Transformers\BookTransformer;

class BookTransformerTest extends TestCase
{
    /** @test */
    public function it_transforms_a_book_model()
    {
        $book = Book::first(); // given a book.
        $transformer = new BookTransformer(); // and a book transformer instance.

        $transformedBook = $transformer->transform($book); // when transforming a book...

        $this->assertIsArray($transformedBook); // then we should see an array with the book api exposed attributes...
        foreach ($this->getAttributes() as $key) {
            $this->assertArrayHasKey($key, $transformedBook);
        }
    }

    /**
     * getAttributes
     *
     * @return array
     */
    private function getAttributes()
    {
        return [
            'title',
            'description',
            'isbn',
            'author_id',
            'updated',
            'released'
            // 'created',
        ];
    }
}
