<?php namespace App\Http\Controllers;

use App\Author;
use App\ApiProject\Transformers\AuthorTransformer;

class AuthorsController extends ApiController
{
    /**
     *
     * @var ApiProject\Transformer\AuthorTransformer
     */
    protected $authorTransformer;

    /**
     * __construct
     *
     * @param ApiProject\Transformer\AuthorTransformer $authorTransformer
     * @return void
     */
    public function __construct(AuthorTransformer $authorTransformer)
    {
        $this->authorTransformer = $authorTransformer;
    }

    /**
     * View all authors.
     *
     * @return void
     */
    public function index()
    {
        $authors = Author::all();
        
        return $this->respond([
            'data' => $this->authorTransformer->transformCollection($authors->toArray())
        ]); // 200 is the default statusCode that is why I omitted it.
    }

}
