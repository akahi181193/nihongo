<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Memo;


class CategoryController extends Controller
{
    public function destroy($id)
    {
        $del_Category = Category::find($id);
        Memo::query()
            ->where('category_id', '=',$id )
            ->delete();

        $del_Category->delete();

        return redirect()->back()->with('success', '削除しました');
    }
}
