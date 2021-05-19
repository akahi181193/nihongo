<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MemoController extends Controller
{
    public function __construct()
    {
    }

    public function store(Request $request)
    {
        $payload = $request->all();
        unset($payload['_token']);
        $payload['user_id'] = Auth::user()->id;

        Memo::query()->create($payload);

        // return redirect()->back()->withErrors(['add-da them']);

        return redirect()->route('home', [
            'category' => $request->category,
            'page' => $request->page,
            'message' => 'add'
        ]);
    }
    public function delete(Request $request, $id)
    {
        $del_Memo = Memo::find($id);

        $del_Memo->delete();

        return  redirect()->route('home', [
            'category' => $request->category,
            'page' => $request->page,
            'message' => 'del'
        ]);
    }

    public function edit ($id)
    {
        $edit_memo = Memo::find($id);

        $categories = Category::query()
        ->where('user_id', '=', Auth::user()->id)
        ->get();

        return view('edit', compact('edit_memo','categories'));
    }
    public function update(Request $request, $id)
    {
        $update = Memo::find($id);
        $update->name = $request->name;
        $update->category_id = $request->category_id;
        $update->description = $request->description;
        $update->save();
        return redirect()->route('home', [
            'category' => $request->category,
            'page' => $request->page,
            'message' => 'update'
        ]);
    }
}
