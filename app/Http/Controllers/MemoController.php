<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Memo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $request->file('images')->storeAs($destination_path, $image_name);
            $payload['images'] = $image_name;
        }

        if ($request->hasFile('audio')) {
            $this->validate(
                $request,
                [
                    'audio' => 'mimes:mp3,mp4,3gb,wav,wma,avi|max:25000',
                ],
                [
                    'audio.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .mp3 .mp4 .3gb .wav .wma .avi ',
                    'audio.max' => 'Hình thẻ giới hạn dung lượng không quá 25M',
                ]
            );
            $destination_path = 'public/audio/memos';
            $audio = $request->file('audio');
            $audio_name = $audio->getClientOriginalName();
            $request->file('audio')->storeAs($destination_path, $audio_name);
            $payload['audio'] = $audio_name;
        }

        Memo::query()->create($payload);

        return redirect()->back()->with('success', '追加しました');
    }

    public function delete(Request $request, $id)
    {
        $del_Memo = Memo::find($id);

        $del_Memo->delete();

        return redirect()->back()->with('success', '削除しました');
    }

    public function update(Request $request, $id)
    {
        $row = Memo::find($id);
        if ($row) {
            $row->name = $request->name;
            $row->category_id = $request->category_id;
            $row->description = $request->description;

            if ($request->hasFile('images')) {
                $destination_path = 'public/images/memos';
                $image = $request->file('images');
                $image_name = $image->getClientOriginalName();
                $request->file('images')->storeAs($destination_path, $image_name);
                $row->images = $image_name;
            }

            if ($request->hasFile('audio')) {
                $this->validate(
                    $request,
                    [
                        'audio' => 'mimes:mp3,mp4,3gb,wav,wma ,avi|max:25000',
                    ],
                    [
                        'audio.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .mp3 .mp4 .3gb .wav .wma .avi ',
                        'audio.max' => 'Hình thẻ giới hạn dung lượng không quá 25M',
                    ]
                );
                $destination_path = 'public/audio/memos';
                $audio = $request->file('audio');
                $audio_name = $audio->getClientOriginalName();
                $request->file('audio')->storeAs($destination_path, $audio_name);
                $row->audio = $audio_name;
            }

            $row->save();
        }

        return redirect()->back()->with('success', '編集しました。');
    }

    public function getMemoById(Request $request, $id)
    {
        $memo = Memo::find($id);

        return response()->json($memo);
    }
}