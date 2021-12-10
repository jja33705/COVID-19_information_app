<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Image;
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
        return Inertia::render('Review/SearchReview', [
            'searchWay' => $request->searchWay,
            'search' => $request->search,
        ]);
    }

    public function getReviews(Request $request)
    {
        $searchWay = $request->searchWay;
        $search = $request->search;
        if ($searchWay == 'keyword') {
            return Review::where('title', 'like', '%' . $search . '%')->latest()->paginate(9)->withQueryString();
        } else if ($searchWay == 'hashtag') {
            return Hashtag::where('contents', $search)->first()->reviews()->latest()->paginate(9)->withQueryString();
        } else if ($searchWay == 'place') {
            return Review::where('place', $search)->latest()->paginate(9)->withQueryString();
        } else if ($searchWay == 'myReview') {
            return Review::where('user_id', auth()->user()->id)->latest()->paginate(9)->withQueryString();
        }
    }

    public function show($id)
    {
        $review = Review::with('hashtags')->find($id);
        $review->viewCount += 1;
        $review->save();
        return Inertia::render('Review/ShowReview', [
            'review' => $review,
            'comments' => $review->comments()->whereNull('parent_id')->orderBy('created_at', 'asc')->with('replies')->get(),
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
            'images.*' => ['image'], //배열안의 값들에 대해 검사
        ]);

        $review = new Review;
        $review->user_id = auth()->user()->id;
        $review->title = $request->title;
        $review->place = $request->place;
        $review->contents = $request->contents;
        $review->contentId = $id;
        $review->save();

        if ($request->hasFile('images')) {
            foreach ($request->images as $imageFile) {
                $image = new Image();
                $name = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                Storage::disk('public')->put('images/' . $name, file_get_contents($imageFile));
                $image->filename = $name;
                $image->review_id = $review->id;
                $image->save();
            }
        }

        if ($request->hashtags) {
            foreach ($request->hashtags as $hashtag) {
                $hashtag = Hashtag::firstOrCreate([
                    'contents' => $hashtag
                ]);
                $review->hashtags()->toggle([$hashtag->id]);
            }
        }
        return Redirect::route('review.show', [
            'id' => $review->id
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Review/EditReview', [
            'review' => Review::with('hashtags')->find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        FacadesRequest::validate([
            'title' => ['required', 'max:30'],
            'contents' => ['required', 'max:300'],
            'images.*' => ['image'], //배열안의 값들에 대해 검사
        ]);

        $review = Review::find($id);
        if ($request->user()->cannot('update', $review)) {
            abort(403);
        }

        if ($request->deleteImageIds) { //원래 있는데 삭제해야 할 이미지 삭제
            foreach ($request->deleteImageIds as $deleteImageId) {
                $image = Image::find($deleteImageId);
                Storage::disk('public')->delete('/images/' . $image->filename);
                $image->delete();
            }
        }

        if ($request->hasFile('images')) { //새로 등록해야 할 이미지 등록
            foreach ($request->images as $imageFile) {
                $image = new Image();
                $name = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                Storage::disk('public')->put('images/' . $name, file_get_contents($imageFile));
                $image->filename = $name;
                $image->review_id = $review->id;
                $image->save();
            }
        }

        $review->title = $request->title;
        $review->contents = $request->contents;

        $review->save();

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
        if ($review->images) {
            foreach ($review->images as $image) {
                Storage::disk('public')->delete('/images/' . $image->filename);
            }
        }
        $review->delete();
        return redirect('/review?searchWay=keyword');
    }
}
