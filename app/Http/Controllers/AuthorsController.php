<?php namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    /**
     * show
     *
     * @param Author $id
     * @return void
     */
    public function show($id)
    {
        $author = Author::find($id);

        if ( ! $author )
        {
            return $this->respondNotFound('Author does not exist.');
        }

        return $this->respond([
            'data' => $this->authorTransformer->transform($author)
        ]);
    }

    /**
     * store an Author
     *
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Author::$rules); // $request->input('email');

        if ( $validator->fails() )
        {
            return $this->respondUnprocessableEntity($validator->errors());
        }

        Author::create($request->all());

        return $this->respondCreated( 'Author successfully created.');
    }

}
