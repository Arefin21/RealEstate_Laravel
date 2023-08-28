<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\State;
use App\Models\Property;

class StateController extends Controller
{
    public function AllState(){
        $state=State::latest()->get();
        return view('backend.state.all_state',compact('state'));
    }
    public function AddState(){
        return view('backend.state.add_state');
    }

    public function StoreState(Request $request ){

        $image=$request->file('state_image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,275)->save('upload/state/'.$name_gen);
        $save_url='upload/state/'.$name_gen;

        State::insert([
            'state_name'=>$request->state_name,
            'state_image'=>$save_url,
        ]);
        $notification=array(
            'message'=>'State Inserted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.state')->with($notification);
    }

    public function EditState($id){

        $state=State::findOrFail($id);
        return view('backend.state.edit_state',compact('state'));
    }

    public function UpdateState(Request $request){

        $state_id=$request->id;

        if($request->file('state_image')){

        $oldImg=State::findOrFail($state_id);
        $imagepath=public_path($oldImg->state_image);

        $image=$request->file('state_image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,275)->save('upload/state/'.$name_gen);
        $save_url='upload/state/'.$name_gen;

        if(file_exists($imagepath)){
            unlink($imagepath);
        }

        State::find($state_id)->update([

            'state_name'=>$request->state_name,
            'state_image'=>$save_url,
        ]);
        $notification=array(
            'message'=>'State Update with Image Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.state')->with($notification);
    }else{
        State::find($state_id)->update([

            'state_name'=>$request->state_name,
        ]);
        $notification=array(
            'message'=>'State Update without Image Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.state')->with($notification);
    }
}
    public function DeleteState($id){

       $state=State::findOrFail($id);
       $img=$state->state_image;
       unlink($img);

       State::findOrFail($id)->delete();
    
    $notification=array(
        'message'=>'State Deleted Successfully',
        'alert-type'=>'success'
    );
    return redirect()->back()->with($notification);

    }
}
    

