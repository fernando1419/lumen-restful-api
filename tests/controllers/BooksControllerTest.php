<?php

namespace tests\controllers;

use App\Book;
use TestCase;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_responds_with_200_code_and_siutable_json_structure_when_there_are_books()
    {
        $books = Book::take(10)->get(); // given some books in DB.

        $this->get('/api/books'); // when call GET request to /api/books

        $this->seeStatusCode(200) // then...
            ->seeJsonStructure([
                'message',
                'data' => [
                    [
                        'title',
                        'description',
                        'isbn',
                        'author_id'
                    ]
                ]
            ])
            ->seeJson(['message' => 'Display all books.']);

        foreach ($books as $book) {
            $this->seeJson([
                'title' => $book->title,
                'description' => $book->description,
                'isbn' => $book->isbn,
                "author_id" => $book->author_id
            ]);
        }
    }

    /** @test */
    public function it_responds_with_200_code_and_siutable_json_structure_when_there_are_NO_books()
    {
        Book::truncate(); // given No books in DB.
        $books = Book::take(10)->get();

        $this->get('/api/books'); // when

        $this->seeStatusCode(200) // then...
            ->seeJsonStructure([
                'message',
                'data' => []
            ])
            ->seeJson(['message' => 'No books found!.'])
            ->assertArrayNotHasKey('title', $books->toArray());
    }
}
