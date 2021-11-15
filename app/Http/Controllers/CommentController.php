<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $comment = new Comment;
        $comment->user_id = auth()->user()->id;
        $comment->contents = $request->contents;
        $comment->review_id = $id;
        $comment->save();

        return Redirect::route('review.show', [
            'id' => $id,
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $review_id = $comment->review_id;
        $comment->delete();

        return Redirect::route('review.show', [
            'id' => $review_id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->contents = $request->contents;
        $comment->save();

        return Redirect::route('review.show', [
            'id' => $comment->review_id,
        ]);
    }
}
