<?php

namespace App\Http\Controllers;

use App\Book;
use App\ApiProject\Transformers\BookTransformer;

class BooksController extends ApiController
{
    /**
     * Retrieve all books.
     *
     * @return json response
     */
    public function index()
    {
        $books   = Book::all();
        $message = ($books->isEmpty()) ? 'No books found!.' : 'Display all books.';

        return $this->respond([
            'message' => $message,
            'data' => (new BookTransformer())->transformCollection($books->toArray())
        ]);
    }
}