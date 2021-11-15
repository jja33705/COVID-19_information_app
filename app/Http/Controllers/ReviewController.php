<?php

namespace App\Http\Controllers;

use App\Models\Comment;
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
        if ($searchWay == 'keyword') {
            return Review::where('title', 'like', '%' . $search . '%')->orderByDesc('viewCount')->paginate(9)->withQueryString();
        } else if ($searchWay == 'hashtag') {
            return Hashtag::where('contents', $search)->first()->reviews()->orderByDesc('viewCount')->paginate(9)->withQueryString();
        } else if ($searchWay == 'place') {
            return Review::where('place', $search)->orderByDesc('viewCount')->paginate(9)->withQueryString();
        }
    }

    public function show($id)
    {
        $review = Review::find($id);
        $review->viewCount += 1;
        $review->save();
        return Inertia::render('Review/ShowReview', [
            'review' => $review,
            'comments' => Comment::where('review_id', $review->id)->latest()->get(),
        ]);
    }

    public function create(Request $request, $id)
    {
        return Inertia::render('Review/CreateReview', [
            'contentId' => $id,
            'place' => $request->place,
        ]);
    }

    public function store(Request $request, $id)
    {
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
        $review->contentId = $id;

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
        return Redirect::route('review.show', [
            'id' => $review->id
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);
        if ($request->user()->cannot('delete', $review)) {
            abort(403);
        }
        $review->delete();
        return Redirect()->back()->back();
    }
}
