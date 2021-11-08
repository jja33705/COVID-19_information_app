<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Review;
use Illuminate\Http\Request;;

use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Review/Review', [
            'searchWay' => $request->searchWay,
            'search' => $request->search,
        ]);
    }

    public function getReviews(Request $request)
    {
        $searchWay = $request->searchWay;
        $search = $request->search;
        if ($searchWay == 'keyWord') {
            return Review::where('title', 'like', '%' . $search . '%')->latest()->paginate(9)->withQueryString();
        } else if ($searchWay == 'hashtag') {
            return Hashtag::where('contents', $search)->first()->reviews()->paginate(9)->withQueryString();
            // return $hashtag->reviews()->paginate(9);
        }
    }

    public function create()
    {
        return Inertia::render('Review/CreateReview');
    }

    public function store(Request $request)
    {
        // dd($request);
        dd($request->hashtags);


        $review = new Hashtag;
    }
}
