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
        
        //Lien ket Model
        
        $memos = Memo::query()
            ->orderBy('created_at', 'desc')
            ->where('user_id', '=', Auth::user()->id)
            ->with('category');

        if (isset($request->category))
        {
            $memos = $memos->where('category_id', '=', $request->category);
        }

        if (isset($request->keyword))
        {
            $memos = $memos->where('name', 'like', '%' . $request->keyword . '%');
        }

        $memos = $memos->paginate(10);

        return view('home', compact('categories', 'memos'));
    }
    public function store(Request $request)
    {
        //_token khong cho ngươi ngoài gửi form vào
        //unset xóa phần tử
        $payload = $request->all();
        unset($payload['_token']);
        $payload['user_id'] = Auth::user()->id;


        Category::query()->create($payload);

        return redirect()->back()->with('success', 'カテゴリを追加しました');
    }
}


