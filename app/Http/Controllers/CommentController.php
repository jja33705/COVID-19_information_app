<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as FacadesRequest;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        FacadesRequest::validate([
            'contents' => ['required', 'max:100'],
        ]);

        $comment = new Comment;
        $comment->user_id = auth()->user()->id;
        $comment->contents = $request->contents;
        $comment->review_id = $id;
        $comment->save();

        return Redirect::route('review.show', [
            'id' => $id,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($request->user()->cannot('delete', $comment)) {
            abort(403);
        }
        $review_id = $comment->review_id;
        $comment->delete();

        return Redirect::route('review.show', [
            'id' => $review_id,
        ]);
    }

    public function update(Request $request, $id)
    {
        FacadesRequest::validate([
            'updateContents' => ['required', 'max:100'],
        ]);


        $comment = Comment::find($id);
        if ($request->user()->cannot('update', $comment)) {
            abort(403);
        }
        $comment->contents = $request->updateContents;
        $comment->save();

        return Redirect::route('review.show', [
            'id' => $comment->review_id,
        ]);
    }
}
