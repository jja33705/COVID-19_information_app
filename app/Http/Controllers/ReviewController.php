<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;;

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
            return Hashtag::where('contents', $search)->first()->reviews()->latest()->paginate(9)->withQueryString();
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
        // dd($request->hashtags);
        // $hashtag_array = json_encode($request->$hashtags);
        $hashtags = array();
        foreach ($request->hashtags as $key => $hashtag) {
            $hashtags[$key] = Hashtag::firstOrCreate([
                'contents' => $hashtag
            ]);
        }

        $review = new Review;
        $review->user_id = auth()->user()->id;
        $review->title = $request->title;
        $review->place = $request->place;
        $review->contents = $request->contents;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('images/' . $name, file_get_contents($image));
            $review->image = $name;
        }
        $review->save();

        foreach ($hashtags as $hashtag) {
            $review->hashtags()->toggle([$hashtag->id]);
        }
        return Redirect::route('review.index', [
            'searchWay' => 'keyWord'
        ]);
    }
}
