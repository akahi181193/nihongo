<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;


class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store( Request $request , $id )
    {
        $user = User::query()
            ->where('id', '=', Auth::user()->id)
            ->get();

        return view('userprofile', compact('user'));
    }
    public function update(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'about' => ['string', 'max:355'],
            
        ]);
        $row = User::find($id);
        if($row){
            $row->name = $request->name;
            $row->email = $request->email;
            $row->sex = $request->sex;
            $row->social = $request->social;
            $row->relationship_status = $request->relationship_status;
            $row->birthday = $request->birthday;
            $row->about = $request->about;
            
            

            if ($request->hasFile('avatar')) {
                $this->validate(
                    $request,
                    [
                        'avatar' => 'mimes:jpg,jpeg,bmp,svg,png,gif|max:60000',
                    ],
                    [
                        'avatar.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi jpg,jpeg,bmp,svg,png ',
                        'avatar.max' => 'Hình thẻ giới hạn dung lượng không quá 60M',
                    ]
                );
                $destination_path = 'public/avatar';
                $avatar = $request->file('avatar');
                $avatar_name = $avatar->getClientOriginalName();
                $request->file('avatar')->storeAs($destination_path, $avatar_name);
                $row['avatar'] = $avatar_name;
                $row->avatar = $avatar_name;
                
            }
            $row->save();
        }
        return redirect()->back()->with('success', '編集しました。');
    }
    public function destroy($id){
        $avatar = User::find($id);
        $avatar_name = $avatar->avatar;
        $destination ="storage/avatar/".$avatar_name;
        $default_avatar= "default.png";
        if( File::exists($destination) && $destination != "storage/avatar/default.png"){
            
            File::delete($destination);
            $avatar['avatar']=$default_avatar;
            $avatar->save();

        }
        
        return redirect()->back()->with('success', '削除しました');
    }
    public function reset(){
        return view('userpassreset');
    }
    public function updatepass(Request $request ,$id){
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'string', 'min:6'],
            'new_confirm_password' => ['same:new_password'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        $user = User::query()
        ->where('id', '=', Auth::user()->id)
        ->get();
        return redirect('home')->with('success', '編集しました');
        
        
    }
    

    
}
