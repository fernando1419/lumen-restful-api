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
        $this->middleware('auth', ['only' => ['store', 'update', 'delete']]);
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
            'message' => 'Displays all authors.',
            'data' => $this->authorTransformer->transformCollection($authors->toArray())
        ]); // 200 is the default statusCode that is why I omitted it.
    }

    /**
     * show
     *
     * @param Author $id
     * @return void
     */
    public function show($authorId)
    {
        $author = Author::find($authorId);

        if (! $author) {
            return $this->respondNotFound('Author does not exist.');
        }

        return $this->respond([
            'data'    => $this->authorTransformer->transform($author),
            'message' => "Information about Author ID: {$authorId}."
        ]);
    }

    /**
     * store an Author
     *
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Author::$rules);

        if ($validator->fails())
        {
            return $this->respondUnprocessableEntity($validator->errors());
        }

        $author = $this->createAuthor($request);

        return $this->setStatusCode(201)->respond([
            'data'    => $this->authorTransformer->transform($author),
            'message' => "Author ID: {$author->id} successfully created.!"
        ]);
    }

    /**
     * update
     *
     * @param Request $request
     * @param mixed $id
     * @return void
     */
    public function update(Request $request, $authorId)
    {
        $author = Author::find($authorId);

        if (! $author) {
            return $this->respondNotFound('Author does not exist.');
        }

        $validator = Validator::make($request->all(), Author::$rules);

        if ($validator->fails())
        {
            return $this->respondUnprocessableEntity($validator->errors());
        }

        $this->updateAuthor($request, $author);

        return $this->respond([
            'data'    =>  $this->authorTransformer->transform($author),
            'message' => "Author ID: {$authorId} successfully updated!."
        ]);
    }

    /**
     * destroy
     *
     * @param mixed $authorId
     * @return void
     */
    public function destroy($authorId)
    {
        $author = Author::find($authorId);

        if (! $author) {
            return $this->respondNotFound('Author does not exist.');
        }
        
        $author->delete();

        return $this->respond([
            'message' => "Author ID: {$authorId} successfully deleted!."
        ]);
    }
    
    /**
     * createAuthor
     *
     * @param Request $request
     * @return void
     */
    private function createAuthor(Request $request)
    {
        return Author::create([
            'name'                   => $request->get('name'),
            'email'                  => $request->get('email'),
            'github'                 => $request->get('github'),
            'twitter'                => $request->get('twitter'),
            'location'               => $request->get('location'),
            'last_article_published' => $request->get('last_article'),
            'some_boolean'           => filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN)
        ]);
    }

    /**
     * updateAuthor
     *
     * @param Request $request
     * @param Author $author
     * @return void
     */
    private function updateAuthor(Request $request, Author $author)
    {
        $author->name                   = $request->get('name');
        $author->email                  = $request->get('email');
        $author->github                 = $request->get('github');
        $author->twitter                = $request->get('twitter');
        $author->location               = $request->get('location');
        $author->last_article_published = $request->get('last_article');
        $author->some_boolean           = filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN);

        return $author->save();
    }
    
}
