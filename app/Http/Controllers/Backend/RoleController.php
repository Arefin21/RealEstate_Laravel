<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermissionExport;
use App\Imports\PermissionImport;
use App\Models\User;
use DB;


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

    public function importPermission(){
        return view('backend.pages.permission.import_permission');
    }

    public function Export(){

        return Excel::download(new PermissionExport, 'permission.xlsx'); 
    }

    public function Import(Request $request){
        Excel::import(new PermissionImport, $request->file('import_file'));

        $notification=array(
            'message'=>'Permission Imported Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }

    public function AllRoles(){
        $roles=Role::all();
        return view('backend.pages.role.all_roles',compact('roles'));
    }

    public function AddRoles(){
        return view('backend.pages.role.add_roles');
    }

    public function StoreRoles(Request $request){
        Role::create([
            'name'=>$request->name,
        ]);
        $notification=array(
            'message'=>'Role Created Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }

    public function EditRoles($id){
        $roles=Role::findOrFail($id);
        return view('backend.pages.role.edit_roles',compact('roles'));
    }

    public function UpdateRoles(Request $request){

        $role_id=$request->id;

        Role::findOrFail($role_id)->update([
            'name'=>$request->name,
        ]);
        $notification=array(
            'message'=>'Role Update Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.roles')->with($notification);

    }
    public function DeleteRoles($id){
        Role::findOrFail($id)->delete();

        $notification=array(
            'message'=>'Role Deleted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }

    //Add Role Permission All method

    public function AddRolesPermission(){
        $roles=Role::all();
        $permissions=Permission::all();
        $permission_groups=User::getpermissionGroups();
        return view('backend.pages.rolesetup.add_roles_permission',compact('roles','permissions','permission_groups'));
    }

    public function RolePermissionStore(Request $request){

        $data=array();
        $permissions=$request->permission;

        foreach($permissions as $key => $item){

            $data['role_id']=$request->role_id;
            $data['permission_id']=$item;

            DB::table('role_has_permissions')->insert($data);

        }

        $notification=array(
            'message'=>'Role Permission Added Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);

    }

    public function AllRolePermission(){

       $roles=Role::all();
       return view('backend.pages.rolesetup.all_roles_permission',compact('roles'));

    }

    public function AdminEditRoles($id){

        $role=Role::findOrFail($id);
        $permissions=Permission::all();
        $permission_groups=User::getpermissionGroups();
        return view('backend.pages.rolesetup.edit_roles_permission',compact('role','permissions','permission_groups'));
    }

    public function AdminRolesUpdate(Request $request,$id){

        $role=Role::findOrFail($id);
        $permissions=$request->permission;

        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }

         $notification=array(
            'message'=>'Role Permission Update Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);

    }

    public function AdminDeleteRoles($id){

        $role=Role::findOrFail($id);
        if(!is_null($role)){
            $role->delete();
        }

        $notification=array(
            'message'=>'Role Permission Delected Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }


}
