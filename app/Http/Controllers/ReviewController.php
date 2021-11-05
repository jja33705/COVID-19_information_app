<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
        return Inertia::render('Review/Review');
    }

    public function getReviews()
    {
        return Review::latest()->paginate(9);
    }
}
