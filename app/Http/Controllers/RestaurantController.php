<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;


class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $category_id = $request->category_id;
        $price = $request->price;

        $sorts = [
            '掲載日が新しい順' => 'created_at desc',
            '価格が安い順' => 'lowest_price asc'
        ];

        $sort_query = [];
        $sorted = "created_at desc";
        
        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }

        if ($keyword !== null) {
            $restaurants = Restaurant::whereHas('categories', function ($query) use ($keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->orWhere('address', 'like', "%{$keyword}%")
            ->orWhere('name', 'like', "%{$keyword}%")
            ->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->total();
        } elseif ($category_id !== null) {
            $restaurants = Restaurant::whereHas('categories', function ($query) use ($category_id) {
                $query->where('categories.id', $category_id);
            })
            ->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->total();
       } elseif ($price !== null) {
        $restaurants = Restaurant::where('lowest_price', '<=', $price)
        ->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
        $total = $restaurants->total();
       } else {
        $restaurants = Restaurant::sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
        $total = $restaurants->total();
       }

       $categories = Category::all();

       return view('restaurants.index', compact('keyword', 'category_id', 'price', 'sorts', 'sorted', 'restaurants', 'categories', 'total'));
    }

    public function show(Restaurant $restaurant)
    {
        return view('restaurants.show', compact('restaurant'));
    }
}