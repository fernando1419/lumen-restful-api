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
			'author_id'   => Author::find(20)->id // existing author with id=20.
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
	public function it_422s_if_a_new_book_request_fails_validation()
	{
		$invalidData = [
			[], // required title and author_id
			['title'     => null, 'author_id' => 2], // required title
			['title'     => 'Valid Book Title', 'author_id' => null], // required author_id
			['title'     => 'u'], // min title lenght = 3
			['author_id' => 'XXX'], // author_id must be numeric
			['title'     => 'valid title', 'author_id' => '164566465458864566564465646'], // author_id must exist in authors table.
		];

		$response = $this->getResponseGivenInvalidDataInRequest($invalidData[0]);
		$this->assertObjectHasAttributes($response->error->message, 'title', 'author_id');
		$this->assertEquals('The title field is required.', $response->error->message->title[0]);
		$this->assertEquals('The author id field is required.', $response->error->message->author_id[0]);

		$response = $this->getResponseGivenInvalidDataInRequest($invalidData[1]);
		$this->assertObjectHasAttributes($response->error->message, 'title');
		$this->assertEquals('The title field is required.', $response->error->message->title[0]);

		$response = $this->getResponseGivenInvalidDataInRequest($invalidData[2]);
		$this->assertObjectHasAttributes($response->error->message, 'author_id');
		$this->assertEquals('The author id field is required.', $response->error->message->author_id[0]);

		$response = $this->getResponseGivenInvalidDataInRequest($invalidData[3]);
		$this->assertObjectHasAttributes($response->error->message, 'title');
		$this->assertEquals('The title must be at least 3 characters.', $response->error->message->title[0]);

		$response = $this->getResponseGivenInvalidDataInRequest($invalidData[4]);
		$this->assertObjectHasAttributes($response->error->message, 'author_id');
		$this->assertEquals('The author id must be a number.', $response->error->message->author_id[0]);

		$response = $this->getResponseGivenInvalidDataInRequest($invalidData[5]);
		$this->assertObjectHasAttributes($response->error->message, 'author_id');
		$this->notSeeInDatabase('books', ['author_id' => $invalidData[5]['author_id']]);
		$this->assertEquals('The author_id value does not exist in table authors', $response->error->message->author_id[0]);
	}

	/**
	 * @coversNothing
	 */
	private function getResponseGivenInvalidDataInRequest($data)
	{
		$request  = $this->post('api/books', $data, ['Accept' => 'application/json']); // when calling the api without any data..
		$response = json_decode($request->response->getContent());
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
		$data = [
			'title'       => 'Updated book Title',
			'description' => 'Updated book Description',
			'isbn'        => '0910234910000000000',
			'author_id'   => Author::first()->id // existing author_id
		];

		$book = Book::first(); // given an existing $book instance in DB.

		$request = $this->put("api/books/{$book->id}", $data); // when calling update method on this instance.

		$response = json_decode($request->response->getContent())->data; // then
		$this->assertResponseStatus(200);
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
			'message'     => "Book ID: 1 successfully updated.!"
		]);
		$this->seeInDatabase('books', [
			'title'       => $response->title,
			'description' => $response->description,
			'isbn'        => $response->isbn,
			'author_id'   => $response->author_id,
		]);
	}

	/** @test */
	public function it_deletes_an_existing_book_given_valid_parameters()
	{
		// given an existing book
		$book = Book::find(10);
		// when calling the api:
		$request = $this->delete("api/books/{$book->id}");

		// should see a 200 status code and a json response
		json_decode($request->response->getContent());
		$this->assertResponseStatus(200);
		$this->notSeeInDatabase('books', ['id' => $book->id]);
		$this->seeJsonStructure(['message']);
		$this->seeJson(['message' => 'Book ID: 10 successfully deleted!.']);
	}
}
