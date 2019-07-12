<?php

namespace tests\controllers;

use TestCase;

class ApiControllerTest extends TestCase
{
    /** @test */
    public function it_responds_with_400_code_and_error_keys_with_non_numeric_parameters_in_requests()
    {
        $url_with_invalid_param = 'api/authors/X';

        $request = $this->get($url_with_invalid_param);

        $request->seeStatusCode(400)
            ->seeJsonStructure($this->getJsonErrorkeys())
            ->assertObjectNotHasAttribute('email', $request);
    }

    /** @test */
    public function it_responds_with_404_code_and_errors_keys_with_unexisting_resources_in_requests()
    {
        $request = $this->get('api/non-existing-url/22');

        $request->seeStatusCode(404)
            ->seeJsonStructure($this->getJsonErrorkeys())
            ->assertObjectNotHasAttribute('email', $request);
    }

    /**
     * getJsonFailureStructure
     *
     * @return array keys retrived indicating error in json responses
     */
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


    // $author = Author::create(['name' => 'John', 'email' => 'john@gmail.com']);
    // /** @test */
    // public function it_should_display_a_json_response_with_()
    // { }
}
