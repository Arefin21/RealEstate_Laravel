<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function AllPermission(){

        $permission=Permission::all();
        return view('backend.pages.permission.all_permission',compact('permission'));

    }

    public function AddPermission(){
        return view('backend.pages.permission.add_permission');
    }

    public function Storepermission(Request $request){
        $permission = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification=array(
            'message'=>'Permission Create Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }

    public function Editpermission($id) {
        $permission=Permission::findOrFail($id);
        return view('backend.pages.permission.edit_permission',compact('permission'));
    }

    public function Updatepermission(Request $request){

        $per_id=$request->id;

        Permission::findOrFail($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification=array(
            'message'=>'Permission Update Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }

    public function Deletepermission($id){

        Permission::findOrFail($id)->delete();

        $notification=array(
            'message'=>'Permission Deleted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }

}