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

	/** @test */
	public function it_creates_a_new_book_given_valid_parameters()
	{
		Book::truncate(); // given No books in DB.

		$data = [
			'title'       => 'New book Title',
			'description' => 'New book Description',
			'isbn'        => '0910234910000000000',
			'author_id'   => 2
		];

		$request  = $this->post('api/books', $data); // when calling api...
		$response = json_decode($request->response->getContent())->data;
        // dd($response); // stdclass with all fields declared in the BookTransformer Class.

		$this->assertResponseStatus(201); // then...
		$this->assertInstanceOf('stdclass', $response);
		$this->seeJsonStructure([
			'data' => [
					'title',
					'description',
					'isbn',
					'author_id',
					'updated',
					'released'
			],
			'message'
		]);
		$this->seeJson([
			'title'       => $response->title,
			'description' => $response->description,
			'isbn'        => $response->isbn,
			'author_id'   => $response->author_id,
			'updated'     => $response->updated,
			'released'    => $response->released,
			'message'     => "Book ID: 1 successfully created.!"
		]);
	}

	/** @test */
	public function it_throws_a_422_if_a_new_book_request_fails_validation()
	{
		//  it tests that required fields of a Book instance are present.
		$request = $this->post('api/books', [], ['Accept' => 'application/json']); // when calling the api without any data..

		$response = json_decode($request->response->getContent()); // dd($response->error->message);

		$this->assertResponseStatus(422); // then..
		$this->assertInstanceOf('stdClass', $response);
		$this->assertObjectHasAttribute('error', $response);
		$this->assertObjectHasAttributes($response->error, 'message', 'url', 'status_code');
		$this->assertObjectHasAttributes($response->error->message, 'title', 'author_id');
		$this->assertEquals('The title field is required.', $response->error->message->title[0]);
		$this->assertEquals('The author id field is required.', $response->error->message->author_id[0]);
	}
}
