<?php

namespace App\Http\Controllers;

use App\Author;
use App\ApiProject\Transformers\AuthorTransformer;

class AuthorsController extends Controller
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
        
        return response()->json([
            'data' => $this->authorTransformer->transformCollection($authors->toArray())
        ], 200);
    }

}
