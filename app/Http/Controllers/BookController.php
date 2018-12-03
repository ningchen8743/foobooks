<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('title')->get();

        return view('books.index')->with([
            'books' => $books
        ]);
    }

    public function show(Request $request, $id)
    {
        $book=Book::find($id);
        return view('books.show')->with(['book'=>$book]);
    }
    /**
     * GET
     * /books/search
     * Show the form to search for a book
     */
    public function search(Request $request)
    {
        return view('books.search')->with([
            'searchTerm' => session('searchTerm', ''),
            'caseSensitive' => session('caseSensitive', false),
            'searchResults' => session('searchResults', []),
        ]);
    }

    /**
     * GET
     * /books/search-process
     * Process the form to search for a book
     */
    public function searchProcess(Request $request)
    {
        # Start with an empty array of search results; books that
        # match our search query will get added to this array
        $searchResults = [];

        # Store the searchTerm in a variable for easy access
        # The second parameter (null) is what the variable
        # will be set to *if* searchTerm is not in the request.
        $searchTerm = $request->input('searchTerm', null);

        # Only try and search *if* there's a searchTerm
        if ($searchTerm) {
            # Open the books.json data file
            # database_path() is a Laravel helper to get the path to the database folder
            # See https://laravel.com/docs/helpers for other path related helpers
            $booksRawData = file_get_contents(database_path('/books.json'));

            # Decode the book JSON data into an array
            # Nothing fancy here; just a built in PHP method
            $books = json_decode($booksRawData, true);

            # Loop through all the book data, looking for matches
            # This code was taken from v0 of foobooks we built earlier in the semester
            foreach ($books as $title => $book) {
                # Case sensitive boolean check for a match
                if ($request->has('caseSensitive')) {
                    $match = $title == $searchTerm;
                    # Case insensitive boolean check for a match
                } else {
                    $match = strtolower($title) == strtolower($searchTerm);
                }

                # If it was a match, add it to our results
                if ($match) {
                    $searchResults[$title] = $book;
                }
            }
        }

        # Redirect back to the search page w/ the searchTerm *and* searchResults (if any) stored in the session
        # Ref: https://laravel.com/docs/redirects#redirecting-with-flashed-session-data
        return redirect('/books/search')->with([
            'searchTerm' => $searchTerm,
            'caseSensitive' => $request->has('caseSensitive'),
            'searchResults' => $searchResults
        ]);
    }# Code to process the form will go here...

    /**
     * GET /books/create
     * Display the form to add a new book
     */
    public function create(Request $request)
    {
        return view('books.create');
    }


    /**
     * POST /books
     * Process the form for adding a new book
     */
    public function store(Request $request)
    {
        # Code will eventually go here to add the book to the database,
        # but for now we'll just dump the form data to the page for proof of concept
        # Validate the request data
        $request->validate([
            'title' => 'required',
            'author' => 'required|regex:/^[\pL\s\-]+$/u',
            'published_year' => 'required|digits:4',
            'cover_url' => 'required|url',
            'purchase_url' => 'required|url'
        ]);

        # Note: If validation fails, it will redirect the visitor back to the form page
        # and none of the code that follows will execute.

        # Code will eventually go here to add the book to the database,
        # but for now we'll just dump the form data to the page for proof of concept

        $book = new Book();
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->published_year = $request->input('published_year');
        $book->cover_url = $request->input('cover_url');
        $book->purchase_url = $request->input('purchase_url');
        $book->save();

        return redirect('/books')->with([
            'alert' => 'Your book was added.'
        ]);
    }


    public function edit($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return redirect('/books')->with([
                'alert' => 'Book not found.'
            ]);
        }

        return view('books.edit')->with([
            'book' => $book
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'author' => 'required',
            'published_year' => 'required|digits:4|numeric',
            'cover_url' => 'required|url',
            'purchase_url' => 'required|url',
        ]);

        $book = Book::find($id);
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->published_year = $request->input('published_year');
        $book->cover_url = $request->input('cover_url');
        $book->purchase_url = $request->input('purchase_url');
        $book->save();

        return redirect('books'.$id.'/edit')->with([
            'alert' => 'Your changes were saved.'
        ]);
    }



    public function deletebook($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return redirect('/books')->with([
                'alert' => 'Book not found.'
            ]);
        }

        $book->delete();
        return redirect('/books')->with([
            'alert' => 'Your book has been deleted.'
        ]);
    }

}


