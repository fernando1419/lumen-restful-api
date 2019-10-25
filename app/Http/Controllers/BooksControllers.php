<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
			'data'    => (new BookTransformer())->transformCollection($books->toArray())
		]);
	}

	/**
	 * show GET('api/books/$bookId)
	 *
	 * @param mixed $bookId
	 * @return void
	 */
	public function show($bookId)
	{
		$book = Book::find($bookId);

		if ( ! $book) {
			return $this->respondNotFound('Book does not exist.');
		}

		return $this->respond([
			'data'    => (new BookTransformer())->transform($book),
			'message' => "Information about Book ID: {$bookId}."
		]);
	}

	/**
	 * store POST('api/books')
	 *
	 * @param Request $request
	 * @return void
	 */
	public function store(Request $request)
	{
		$validation = Validator::make($request->all(), Book::rules()); // ->validate();

        if ($validation->fails()) {
			return $this->respondUnprocessableEntity($validation->errors());
		}

		$newBook = Book::create($request->all());

		return $this->setStatusCode(201)->respond([
			'data'    => (new BookTransformer())->transform($newBook),
			'message' => "Book ID: {$newBook->id} successfully created.!"
		]);
	}
}
