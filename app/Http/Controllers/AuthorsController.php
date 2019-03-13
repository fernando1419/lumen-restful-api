<?php

namespace App\Http\Controllers;

use App\Author;

class AuthorsController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        
        return response()->json([
            'data' => $authors 
        ], 200);
    }
}
