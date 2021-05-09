<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        $memos = Memo::query()
            ->where('user_id', '=', Auth::user()->id)
            ->where('category_id', '=', $request->category)
            ->with('category')
            ->paginate(10);

        return view('home', compact('categories', 'memos'));
    }
}
