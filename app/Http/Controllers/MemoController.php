<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Memo;
use Illuminate\Http\JsonResponse;
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

        if ($request->hasFile('images')) {
            $destination_path = 'public/images/memos';
            $image = $request->file('images');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('images')->storeAs($destination_path, $image_name);
            $payload['images'] = $image_name;
        }
        Memo::query()->create($payload);

        return redirect()->back()->with('success', '追加しました');
    }

    public function delete(Request $request, $id)
    {
        $del_Memo = Memo::find($id);

        $del_Memo->delete();

        return redirect()->back()->with('success', '削除しました','alert','danger');
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
        $row = Memo::find($id);
        if ($row) {
            $row->name = $request->name;
            $row->category_id = $request->category_id;
            $row->description = $request->description;
            $row->save();
           
            return response()->json(['message' => '編集しました。'])->status(JsonResponse::HTTP_ACCEPTED);
        }

        return response()->json(['error_message' => '存在しない'])->status(JsonResponse::HTTP_NOT_FOUND);
    }

    public function getMemoById(Request $request, $id) {
        $memo = Memo::find($id);

        return response()->json($memo);
    }
}
