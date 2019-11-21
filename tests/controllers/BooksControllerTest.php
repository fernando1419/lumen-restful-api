<?php

namespace tests\controllers;

use App\Book;
use App\Author;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksControllerTest extends ApiControllerTest
{
	use DatabaseTransactions;

	/** @test */
	public function it_responds_with_200_code_and_siutable_json_structure_when_there_are_books()
	{
		$books = Book::take(10)->get(); // given some books in DB.

		$this->getJson('/api/books'); // when call GET request to /api/books

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

		$this->getJson('/api/books'); // when

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
		$this->getJson('api/books/1');

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
		$response = $this->getJson('api/books/1')->data;

		// then...
		$this->seeStatusCode(200);
		$this->assertInstanceOf('\stdClass', $response);
		$this->assertObjectHasAttributes($response, 'title', 'description', 'isbn', 'author_id', 'updated', 'released');
		$this->assertEquals($book->title, $response->title);
		$this->assertEquals($book->description, $response->description);
		$this->assertEquals($book->isbn, $response->isbn);
		$this->assertEquals($book->author_id, $response->author_id);
	}

	/** @test
	 * this test is used by the show, update and delete methods (implemented using the 'check-route' middleware)
	 */
	public function it_400s_if_a_bookId_parameter_is_not_numeric()
	{
		// when calling a GET request at the Book API given a not numeric bookId as parameter...
		$this->getJson('api/books/13Xa');

		// then...
		$this->assertResponseStatus(400);

		$this->seeJsonStructure($this->getJsonErrorkeys());

		$this->seeJson([
			'message'     => "Bad request. The parameter '13Xa' must be numeric.",
			'status_code' => 400,
			'url'         => 'http://localhost/api/books/13Xa'
		]);
	}

	/** @test
	 * this test is used by the show, update and delete methods.
	 */
	public function it_404s_if_a_book_is_not_found()
	{
		// given a non-existing NUMERIC bookId... (BECAUSE A NON-NUMERIC PARAMETER IS CONTROLLED BY MIDDLEWARE)
		$non_existing_bookId = '12312646545664652465554655656546555555555555555500';

		$this->getJson("api/books/{$non_existing_bookId}"); // when...

		$this->assertResponseStatus(404); // then...

		$this->seeJsonStructure($this->getJsonErrorkeys());

		$this->seeJson([
			'message'     => 'Book does not exist.',
			'status_code' => 404,
			'url'         => 'http://localhost/api/books/12312646545664652465554655656546555555555555555500'
		]);
	}

	/** @test */
	public function it_creates_a_new_book_given_valid_parameters()
	{
		$this->markTestIncomplete('Pending test');

		// Book::truncate(); // given No books in DB.

		// $bookData = [
		// 	'title'       => 'New book Title',
		// 	'description' => 'New book Description',
		// 	'isbn'        => '0910234910000000000',
		// 	'author_id'   => Author::find(20)->id // existing author with id=20.
		// ];

		// $response = $this->getJson('/api/books', 'POST', $bookData)->data;
		// // dd($response); // stdclass with all fields declared in the BookTransformer Class.

		// $this->assertResponseStatus(201); // then...
		// $this->assertInstanceOf('stdclass', $response);
		// $this->seeJsonStructure([
		// 	'data' => [
		// 		'title',
		// 		'description',
		// 		'isbn',
		// 		'author_id',
		// 		'updated',
		// 		'released'
		// 	],
		// 	'message'
		// ]);
		// $this->seeJson([
		// 	'title'       => $response->title,
		// 	'description' => $response->description,
		// 	'isbn'        => $response->isbn,
		// 	'author_id'   => $response->author_id,
		// 	'updated'     => $response->updated,
		// 	'released'    => $response->released,
		// 	'message'     => "Book ID: 1 successfully created.!"
		// ]);
	}

	/** @test */
	public function it_422s_if_a_new_book_request_fails_validation()
	{
		$this->markTestIncomplete('Pending test');

		// $invalidData = [
		//     [], // required title and author_id
		//     ['title'     => null, 'author_id' => 2], // required title
		//     ['title'     => 'Valid Book Title', 'author_id' => null], // required author_id
		//     ['title'     => 'u'], // min title lenght = 3
		//     ['author_id' => 'XXX'], // author_id must be numeric
		//     ['title'     => 'valid title', 'author_id' => '164566465458864566564465646'], // author_id must exist in authors table.
		// ];

		// $response = $this->getResponseGivenInvalidDataInRequest($invalidData[0]);
		// $this->assertObjectHasAttributes($response->error->message, 'title', 'author_id');
		// $this->assertEquals('The title field is required.', $response->error->message->title[0]);
		// $this->assertEquals('The author id field is required.', $response->error->message->author_id[0]);

		// $response = $this->getResponseGivenInvalidDataInRequest($invalidData[1]);
		// $this->assertObjectHasAttributes($response->error->message, 'title');
		// $this->assertEquals('The title field is required.', $response->error->message->title[0]);

		// $response = $this->getResponseGivenInvalidDataInRequest($invalidData[2]);
		// $this->assertObjectHasAttributes($response->error->message, 'author_id');
		// $this->assertEquals('The author id field is required.', $response->error->message->author_id[0]);

		// $response = $this->getResponseGivenInvalidDataInRequest($invalidData[3]);
		// $this->assertObjectHasAttributes($response->error->message, 'title');
		// $this->assertEquals('The title must be at least 3 characters.', $response->error->message->title[0]);

		// $response = $this->getResponseGivenInvalidDataInRequest($invalidData[4]);
		// $this->assertObjectHasAttributes($response->error->message, 'author_id');
		// $this->assertEquals('The author id must be a number.', $response->error->message->author_id[0]);

		// $response = $this->getResponseGivenInvalidDataInRequest($invalidData[5]);
		// $this->assertObjectHasAttributes($response->error->message, 'author_id');
		// $this->notSeeInDatabase('books', ['author_id' => $invalidData[5]['author_id']]);
		// $this->assertEquals('The author_id value does not exist in table authors', $response->error->message->author_id[0]);
	}

	/**
	 * @coversNothing
	 */
	private function getResponseGivenInvalidDataInRequest($data)
	{
		$response  = $this->getJson('api/books', 'POST', $data, ['Accept' => 'application/json']);
		// dd($response);

		$this->assertResponseStatus(422); // then..
		$this->assertInstanceOf('stdClass', $response);
		$this->assertObjectHasAttribute('error', $response);
		$this->assertObjectHasAttributes($response->error, 'message', 'url', 'status_code');

		return $response;
	}

	/** @test */
	public function it_updates_an_existing_book_given_valid_parameters()
	{
		$this->markTestIncomplete('Pending test');

		// $bookData = [
		// 	'title'       => 'Updated book Title',
		// 	'description' => 'Updated book Description',
		// 	'isbn'        => '0910234910000000000',
		// 	'author_id'   => Author::first()->id // existing author_id
		// ];

		// $book = Book::first(); // given an existing $book instance in DB.

		// // when calling update method on this instance.
		// $response = $this->getJson("/api/books/{$book->id}", 'PUT', $bookData)->data;

		// $this->assertResponseStatus(200);
		// $this->assertInstanceOf('stdclass', $response);
		// $this->seeJsonStructure([
		// 	'data' => [
		// 			'title',
		// 			'description',
		// 			'isbn',
		// 			'author_id',
		// 			'updated',
		// 			'released'
		// 	],
		// 	'message'
		// ]);
		// $this->seeJson([
		// 	'title'       => $response->title,
		// 	'description' => $response->description,
		// 	'isbn'        => $response->isbn,
		// 	'author_id'   => $response->author_id,
		// 	'updated'     => $response->updated,
		// 	'released'    => $response->released,
		// 	'message'     => "Book ID: 1 successfully updated.!"
		// ]);
		// $this->seeInDatabase('books', [
		// 	'title'       => $response->title,
		// 	'description' => $response->description,
		// 	'isbn'        => $response->isbn,
		// 	'author_id'   => $response->author_id,
		// ]);
	}

	/** @test */
	public function it_deletes_an_existing_book_given_valid_parameters()
	{
		$this->markTestIncomplete('Pending test');
		// 	// given an existing book
		// 	$book = Book::find(10);

		// 	// when calling the api:
		// 	$this->getJson("api/books/{$book->id}", 'DELETE');

		// 	// should see a 200 status code and a json response
		// 	$this->assertResponseStatus(200);
		// 	$this->notSeeInDatabase('books', ['id' => $book->id]);
		// 	$this->seeJsonStructure(['message']);
		// 	$this->seeJson(['message' => 'Book ID: 10 successfully deleted!.']);
	}
}
