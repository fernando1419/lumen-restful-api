<?php

namespace tests\controllers;

use App\Book;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksControllerTest extends ApiControllerTest
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
				'title'       => $book->title,
				'description' => $book->description,
				'isbn'        => $book->isbn,
				"author_id"   => $book->author_id
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

	/** @test */
	public function it_fetches_a_single_book()
	{
		// given...
		$book = Book::first();

		// when...
		json_decode($this->call('GET', 'api/books/1')->getContent());

		// then...
		$this->seeStatusCode(200);
		$this->seeJsonStructure([
			'data' => [
				'title',
				'description',
				'isbn',
				'author_id'
			],
			'message'
		]);
		$this->seeJson([
			'title'       => $book->title,
			'description' => $book->description,
			'isbn'        => $book->isbn,
			'author_id'   => $book->author_id,
			'message'     => "Information about Book ID: {$book->id}."
		]);
	}

	/** @test */
	public function it_fetches_a_single_book_another_way()
	{
		// given...
		$book = Book::first();

		// when....
		$request  = $this->get('api/books/1');
		$response = json_decode($request->response->getContent())->data;

		// then...
		$this->seeStatusCode(200);
		$this->assertInstanceOf('\stdClass', $response);
		$this->assertObjectHasAttributes($response, 'title', 'description', 'isbn', 'author_id', 'updated', 'released');
		$this->assertEquals($book->title, $response->title);
		$this->assertEquals($book->description, $response->description);
		$this->assertEquals($book->isbn, $response->isbn);
		$this->assertEquals($book->author_id, $response->author_id);
	}

	/** @test */
	public function it_404s_if_a_book_is_not_found()
	{
		$non_existing_bookId = 'XXX';
		$request             = $this->get("api/books/{$non_existing_bookId}");
		json_decode($request->response->getContent());

		$this->assertResponseStatus(404);

		$this->seeJsonStructure([
			'error' => [
				'message',
				'status_code',
				'url'
			]
		]);

		$this->seeJson([
			'message'     => 'Book does not exist.',
			'status_code' => 404
		]);
	}

	private function assertObjectHasAttributes()
	{
		$args   = func_get_args(); // get all parameters.
		$object = array_shift($args); // first parameter is the instance

		foreach ($args as $attribute) {
			$this->assertObjectHasAttribute($attribute, $object);
		}
	}
}
