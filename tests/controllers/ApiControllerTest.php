<?php

namespace tests\controllers;

use TestCase;

class ApiControllerTest extends TestCase
{
	/** @test */
	public function it_responds_with_400_code_and_error_keys_when_request_has_non_numeric_parameters()
	{
		$url_with_invalid_param = 'api/authors/X';

		$request = $this->get($url_with_invalid_param);

		$request->seeStatusCode(400)
				->seeJsonStructure($this->getJsonErrorkeys());
	}

	/** @test */
	public function it_responds_with_404_code_and_errors_keys_when_calling_unexisting_resource()
	{
		$request = $this->get('api/non-existing-url/afdasdfafasdf');

		$request->seeStatusCode(404)
				->seeJsonStructure($this->getJsonErrorkeys());
	}

	protected function getJsonErrorkeys()
	{
		return [
			"error" => [
				"message",
				"status_code",
				"url"
			]
		];
	}

	protected function assertObjectHasAttributes()
	{
		$args   = func_get_args(); // get all parameters.
		$object = array_shift($args); // first parameter is the instance

		foreach ($args as $attribute) {
			$this->assertObjectHasAttribute($attribute, $object);
		}
	}

	/**
	 * getJson
	 *
	 * @param mixed $uri
	 * @param mixed $method (GET, PUT, POST, DELETE)
	 * @param mixed $parameters
	 * @return void
	 */
	protected function getJson($uri, $method = 'GET', $data = [], $headers = [])
	{
		return json_decode($this->call($method, $uri, $data, $headers)->getContent());
	}
}
