<?php

namespace App\Http\Controllers; 

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function AdminDashboard(){

        return view('admin.index');

    }//End Method

    public function AdminLogout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $notification = [
            'message' => 'Admin Logout Successfully',
            'alert-type' => 'success'
        ];
        return redirect('/admin/login')->with($notification);
    }

    public function AdminLogin(){
        return view('admin.admin_login');
    }

    public function AdminProfile(){
        $id=Auth::user()->id;
        $profileData=User::find($id);
        return view('admin.admin_profile_view',compact('profileData'));
    }
    public function AdminProfileStore(Request $request){

        $id=Auth::user()->id;
        $data=User::find($id);
        $data->username=$request->username;
        $data->name=$request->name;
        $data->email=$request->email;
        $data->phone=$request->phone;
        $data->address=$request->address;
        
        if($request->file('photo')){
            $file=$request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo']=$filename;
        }
        $data->save();

        $notification=array(
            'message'=>'Admin Profile Update Successfully',
            'alert-type'=>'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AdminChangePassword(){
        $id=Auth::user()->id;
        $profileData=User::find($id);
        return view('admin.admin_change_password',compact('profileData'));
    }
    // public function AdminUpdatePassword(Request $request){
    //     //Validation
    //     //dd($request);
    //     $request->validate([

    //         'old_password' => 'required',
    //         'new_password' => 'required|confirmed',

    //     ]);
    //     //Match The Old Password

    //     if (!Hash::check($request->old_password, auth()->user()->password)){

    //         $notification=array(
    //             'message'=>'Old Password Does not Match!',
    //             'alert-type'=>'error'
    //             );
               
    //         return back()->with($notification);  
    //     }

    //     //Update new password
    //     User::whereId(auth()->user()->id)->update([
    //         'password'=> Hash::make($request->new_password)
    //     ]);

    //     $notification=array(
    //         'message'=>'Password Change Successfully ',
    //         'alert-type'=>'success'
    //     );
    //     return back()->with($notification);
    // }
    public function AdminUpdatePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|confirmed',
    ]);

    // Match The Old Password
    if (!Hash::check($request->old_password, auth()->user()->password)) {
        $notification = [
            'message' => 'Old Password Does not Match!',
            'alert-type' => 'error'
        ];
        return back()->with($notification);
    }

    // Update new password
    User::whereId(auth()->user()->id)->update([
        'password' => Hash::make($request->new_password)
    ]);

    $notification = [
        'message' => 'Password Change Successfully',
        'alert-type' => 'success'
    ];
    return back()->with($notification);
}


//Agent All Method----------------------------------


    public function AllAgent(){

        $allagent=User::where('role','agent')->get();
        return view('backend.agentuser.all_agent',compact('allagent'));

    }
    public function AddAgent(){
        return view('backend.agentuser.add_agent');
    }

    public function StoreAgent(Request $request){
        User::insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'password'=>Hash::make($request->password),
            'role'=>'agent',
            'status'=>'active',
        ]);
        $notification = [
            'message' => 'Agent Created Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.agent')->with($notification);
    }

    public function EditAgent($id){
        $allagent=User::findOrFail($id);
        return view('backend.agentuser.edit_agent',compact('allagent'));
    }

    public function UpdateAgent(Request $request){

        $user_id=$request->id;

        User::findOrFail($user_id)->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ]);
        $notification = [
            'message' => 'Agent Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.agent')->with($notification);
    }

    public function DeleteAgent($id){
       

        User::findOrFail($id)->delete();
        $notification = [
            'message' => 'Agent Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }

    public function changeStatus(Request $request){

            $user=User::find($request->user_id);
            
            $user->status=$request->status;
            $user->save();

            return response()->json(['success'=>'Status Change Successfully']);
    }

    //Admin User All Method

    public function AllAdmin(){

        $alladmin=User::where('role','admin')->get();
        return view('backend.pages.admin.all_admin',compact('alladmin'));
    }

    public function AddAdmin(){
        $roles=Role::all();
        return view('backend.pages.admin.add_admin',compact('roles'));
    }

    public function StoreAdmin(Request $request){

        $user=new User();
        $user->username=$request->username;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->phone=$request->phone;
        $user->address=$request->address;
        $user->password=Hash::make($request->password);
        $user->role='admin';
        $user->status='active';
        $user->save();

        if($request->roles){
            $user->assignRole($request->roles);
        }
        $notification = [
            'message' => 'New Admin User Inserted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.admin')->with($notification);
    }

    public function EditAdmin($id){

        $user=User::findOrFail($id);
        $roles=Role::all();
        return view('backend.pages.admin.edit_admin',compact('user','roles'));
    }

    public function UpdateAdmin(Request $request,$id){

        $user= User::findOrFail($id);
        $user->username=$request->username;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->phone=$request->phone;
        $user->address=$request->address;
        $user->role='admin';
        $user->status='active';
        $user->save();

        $user->roles()->detach();
        if($request->roles){
            $user->assignRole($request->roles);
        }
        $notification = [
            'message' => 'New Admin User Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.admin')->with($notification);

    }

    public function DeleteAdmin($id){
        $user=User::findOrFail($id);
        if(!is_null($user)){
            $user->delete();
        }
        $notification = [
            'message' => ' Admin User Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
    
}
