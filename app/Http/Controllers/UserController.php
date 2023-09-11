<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Schedule;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('frontend.home.index');
    }

    public function UserProfile(){
        $id=Auth::user()->id;
        $userData=User::find($id);
        return view('frontend.dashboard.edit_profile',compact('userData'));
    }

    public function UserProfileStore(Request $request){
        $id=Auth::user()->id;
        $data=User::find($id);
        $data->username=$request->username;
        $data->name=$request->name;
        $data->email=$request->email;
        $data->phone=$request->phone;
        $data->address=$request->address;

        if($request->file('photo')){
            $file=$request->file('photo');
            @unlink(public_path('upload/user_images/'.$data->photo));
            $fileName=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$fileName);
            $data['photo']=$fileName;
        }
        $data->save();
    
        $notification = [
            'message' => 'User Profile Update Successfully',
            'alert-type' => 'info'
        ];

        return redirect()->back()->with($notification);
    }
    public function UserLogout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $notification = [
            'message' => 'User Logout Successfully',
            'alert-type' => 'success'
        ];
        return redirect('/login')->with($notification);
    }
    public function UserChangePassword(){
        return view('frontend.dashboard.change_password');
    }

    public function UserPasswordUpdate(Request $request){
        $request->validate([
            'old_password'=>'required',
            'new_password'=>'required|confirmed'
        ]);

        if(!Hash::check($request->old_password,auth::user()->password)){
            $notification=array(
                'message'=>'Old Password does not Match !',
                'alert-type'=>'error'
            );
            return back()->with($notification);
        }
        User::whereId(auth()->user()->id)->update([
            'password'=>Hash::make($request->new_password)
        ]);
        $notification=array(
            'message'=>' Password Update Successfully ',
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }

    public function UseruserScheduleRequest(){

        $id=Auth::user()->id;
        $userData=User::find($id);

        $srequest=Schedule::where('user_id',$id)->get();

        return view('frontend.message.schedule_request',compact('userData','srequest'));

    }

    public function LiveChat(){
        $id=Auth::user()->id;
        $userData=User::find($id);
        return view('frontend.dashboard.live_chat',compact('userData'));
    }
}