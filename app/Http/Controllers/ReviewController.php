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
            $hashtag = Hashtag::where('contents', $search)->first();
            return $hashtag->reviews()->paginate(9);
        }
    }
}
