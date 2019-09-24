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
			->seeJsonStructure($this->getJsonErrorkeys())
			->assertObjectNotHasAttribute('email', $request);
	}

	/** @test */
	public function it_responds_with_404_code_and_errors_keys_when_calling_unexisting_resource()
	{
		$request = $this->get('api/non-existing-url/22');

		$request->seeStatusCode(404)
			->seeJsonStructure($this->getJsonErrorkeys())
			->assertObjectNotHasAttribute('email', $request);
	}

	private function getJsonErrorkeys()
	{
		return [
			"error" => [
				"message",
				"status_code",
				"url"
			]
		];
	}
}
