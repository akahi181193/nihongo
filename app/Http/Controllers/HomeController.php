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
            ->orderBy('created_at', 'desc')
            ->where('user_id', '=', Auth::user()->id)
            ->with('category');

        if (isset($request->category))
        {
            $memos = $memos->where('category_id', '=', $request->category);
        }

        $memos = $memos->paginate(10);

        return view('home', compact('categories', 'memos'));
    }
}
