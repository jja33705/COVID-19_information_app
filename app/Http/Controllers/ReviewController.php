<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;


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
        session(['previous-url' => url()->previous()]);
        $review = Review::find($id);
        $review->viewCount += 1;
        $review->save();
        return Inertia::render('Review/ShowReview', [
            'review' => $review,
            'comments' => Comment::where('review_id', $review->id)->whereNull('parent_id')->orderBy('created_at', 'asc')->with('replies')->get(),
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
        FacadesRequest::validate([
            'title' => ['required', 'max:30'],
            'contents' => ['required', 'max:300'],
            'image' => ['nullable', 'image'],
        ]);

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

    public function edit($id)
    {
        return Inertia::render('Review/EditReview', [
            'review' => Review::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        FacadesRequest::validate([
            'title' => ['required', 'max:30'],
            'contents' => ['required', 'max:300'],
            'image' => ['nullable'],
        ]);

        $review = Review::find($id);
        if ($request->user()->cannot('update', $review)) {
            abort(403);
        }

        if ($request->hasFile('image')) { //image 새로 선택했을때
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('images/' . $name, file_get_contents($image));
            Storage::disk('public')->delete('/images/' . $review->image); //원래파일 삭제
            $review->image = $name;
        }

        if (!$request->image) { //image파일이름(문자열로 오면) 오지 않으면 선택취소한거.
            Storage::disk('public')->delete('/images/' . $review->image);
            $review->image = null;
        }

        $review->title = $request->title;
        $review->contents = $request->contents;

        //해쉬태그 삭제하고 다시 만듬
        foreach ($review->hashtags as $hashtag) {
            $review->hashtags()->toggle($hashtag->id);
        }

        foreach ($request->hashtags as $hashtag) {
            $newHashtags = Hashtag::firstOrCreate([
                'contents' => $hashtag
            ]);
            $review->hashtags()->toggle($newHashtags->id);
        }

        $review->save();

        return Redirect::route('review.show', [
            'id' => $review->id,
        ]);
    }

    //리뷰 삭제
    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);
        if ($request->user()->cannot('delete', $review)) {
            abort(403);
        }
        if ($review->image) {
            Storage::disk('public')->delete('/images/' . $review->image);
        }
        $review->delete();
        return redirect(session('previous-url'));
    }
}
