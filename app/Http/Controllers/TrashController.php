<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    function __construct()
    {
    }

    public function index()
    {
        $memos = Memo::onlyTrashed()->get();
        return view('trash', compact('memos'));
    }

    public function destroy($id)
    {
        $memo = Memo::withTrashed()->find($id);
        if ($memo) {
            $memo->forceDelete();
        }
        return redirect()->back();
    }

    public function restore($id)
    {
        $memo = Memo::withTrashed()->find($id);
        if ($memo) {
            $memo->restore();
        }
        return redirect()->back();
    }
}
