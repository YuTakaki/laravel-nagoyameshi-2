<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;

         if ($keyword !== null) {
            $users = User::where('name', 'like', "%{$keyword}%")->orwhere('kana', 'like', "%{$keyword}%")->paginate(15);
            $total = $users->total();
         } else {
            $users = User::paginate(15);
            $total = User::all()->count();
         }

        return view('admin.users.index', compact('users', 'total', 'keyword'));
    }

    public function show(User $user)
    {
      return view('admin.users.show', compact('user'));
    }


}
