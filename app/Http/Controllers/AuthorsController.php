<?php

namespace App\Http\Controllers;

use App\Author;

class AuthorsController extends Controller
{
    public function index()
    {
        $authors = Author::all();
     
        return response()->json([
            'data' => $this->transformCollection($authors) 
        ], 200);
    }

    /**
     * transformCollection
     *
     * @param mixed $authors
     * @return void
     */
    private function transformCollection($authors)
    {        
        return array_map([$this, 'transform'], $authors->toArray());
    }

    /**
     * transform
     *
     * @param mixed $author
     * @return void
     */
    private function transform($author)
    {
        // The apis will work on the keys rather than the name of the fields in authors DB table, that is why tranform is necessary.
        return [
            'name'         => $author['name'],
            'email'        => $author['email'],
            'github'       => $author['github'],
            'twitter'      => $author['twitter'],
            'last_article' => $author['last_article_published'],
            'active'       => (boolean) $author['some_boolean']
        ]; 
    }

}
