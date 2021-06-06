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
        $memos = Memo::onlyTrashed()->with('category')->get();

        $categories = Category::query()
            ->withTrashed()
            ->withCount('deletedMemos')
            ->get();

        $categories = $categories->filter(function ($category) {
            return $category->deleted_memos_count > 0;
        });

        return view('trash', compact('categories', 'memos'));
    }

    public function destroyMemo($id)
    {
        $memo = Memo::withTrashed()->find($id);
        if ($memo) {
            $memo->forceDelete();
        }
        return redirect()->back();
    }

    public function restoreMemo($id)
    {
        $memo = Memo::withTrashed()->find($id);
        if ($memo) {
            $category = Category::withTrashed()->find($memo->category_id);

            if ($category) {
                $category->restore();
            }
            $memo->restore();
        }

        return redirect()->back();
    }

    public function destroyCategory($id)
    {
        $category = Category::withTrashed()->find($id);

        if ($category) {
            Memo::query()
                ->where('category_id', '=', $id)
                ->delete();

            $category->delete();
        }

        return redirect()->back();
    }

    public function restoreCategory($id)
    {
        $category = Category::withTrashed()->find($id);

        if ($category) {
            $category->restore();

            Memo::query()->where('category_id', '=', $id)->restore();
        }

        return redirect()->back();
    }
}
