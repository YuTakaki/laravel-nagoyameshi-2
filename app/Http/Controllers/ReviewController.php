<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        Review::table('reviews')->where('restaurant_id', $restaurant->id)->orderBy('created_at', 'desc')->paginate(3);
    }

    public function create(Restaurant $restaurant)
    {
        return view('reviews.create', compact('restaurant'));
    }

    public function store()
    {
        $request->validate([
            'score' => ['required', 'digits_between:1-5'],
            'content' => ['required']
        ]);

        $review->score = $request->input('score');
        $review->content = $request->input('content');
        $review->restaurant_id = $request->input('restaurant_id');
        $review->user_id = $request->input('user_id');

        $review->save();

        return redirect()->route('reviews.index', compact('review'))->with('flash_message', 'レビューを投稿しました。');
    }

    public function edit(Restaurant $restaurant, Review $reviews)
    {
        if ($user->id !== Review::user_id()) {
            return redirect()->route('reviews.index')->with('error_message', '不正なアクセスです。');
        }

        return view('reviews.edit', compact('restaurant', 'reviews', 'user'));
    }

    public function update(Request $request, Review $reviews)
    {
        $request->validate([
            'score' => ['required', 'digits_between:1-5'],
            'content' => ['required']
        ]);

        if ($user->id !== Review::user_id()) {
            return redirect()->route('reviews.index')->with('error_message', '不正なアクセスです。');
        }

        $review->score = $request->input('score');
        $review->content = $request->input('content');
        $review->restaurant_id = $request->input('restaurant_id');
        $review->user_id = $request->input('user_id');

        $review->save();

        return redirect()->route('reviews.index', compact('review'))->with('flash_message', 'レビューを編集しました');
    }

    public function destroy(Review $reviews)
    {
        if ($user->id !== Review::user_id()) {
            return redirect()->route('reviews.index')->with('error_message', '不正なアクセスです。');
        }

        $review->delete();

        return redirect()->route('reviews.index', compact('review'))->with('flash_message', 'レビューを削除しました。');
    }
}
