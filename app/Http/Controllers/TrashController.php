<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    function __construct()
    {
    }

    public function index()
    {
        // $categories = Category::query()
        //     ->where('user_id', '=', Auth::user()->id);
   
        // $memos = Memo::onlyTrashed()
        //     ->where('user_id', '=', Auth::user()->id)
        //     ->with('category')         
        //     ->get();
        $memos = Memo::onlyTrashed()->get();
        $category = Category::onlyTrashed()->get();
        return view('trash', compact('category','memos'));
    }

    public function destroy($id)
    {
        $memo = Memo::withTrashed()->find($id);
        $category = Category::withTrashed()->find($id);
        if ($memo) {
            $memo->forceDelete();
        }
        if ($category) {

            $category->forceDelete();

        }
        return redirect()->back();
    }

    public function restore($id)
    {
        $memo = Memo::withTrashed()->find($id);
        $category = Category::withTrashed()->find($id);
        if ($memo) {
            $memo->restore();
        }
        if ($category) {
            $category->restore();
            Memo::query()->where('category_id', '=',$id )->restore();
        }
        return redirect()->back();
    }
}
