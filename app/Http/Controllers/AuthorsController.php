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

        $this->createAuthor($request);
        
        return $this->respondCreated('Author successfully created.');
    }

    /**
     * createAuthor
     *
     * @param Request $request
     * @return void
     */
    private function createAuthor(Request $request)
    {
        $author = new Author();
        $author->name                   = $request->get('name');
        $author->email                  = $request->get('email');
        $author->github                 = $request->get('github');
        $author->twitter                = $request->get('twitter');
        $author->location               = $request->get('location');
        $author->last_article_published = $request->get('last_article');
        $author->some_boolean           = filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN);
        $author->save();
    }
    

}
