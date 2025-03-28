<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        $books = Book::when($title, function ($query, $title) {
            return $query->title($title);
        });

        $books = match($filter) {
            'popular_most' => $books->withReviewsCount()->orderBy('reviews_count', 'desc'),
            'popular_least' => $books->withReviewsCount()->orderBy('reviews_count', 'asc'),
            'highest_rated_best' => $books->highestRated()->orderBy('reviews_avg_rating', 'desc'),
            'highest_rated_worst' => $books->highestRated()->orderBy('reviews_avg_rating', 'asc'),
            default => $books->latest()->withAvgRating()->withReviewsCount()
        };

        $books = $books->get();

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return view(
            'books.show',
            [
                'book' => Book::with([
                    'reviews' => fn ($query) => $query->latest()
                ])->withAvgRating()->withReviewsCount()->findOrFail($id)
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
