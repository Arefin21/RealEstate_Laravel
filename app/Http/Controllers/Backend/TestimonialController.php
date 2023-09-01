<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\State;
use App\Models\Property;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function AllTestimonials(){
        $testimonial=Testimonial::latest()->get();
        return view('backend.testimonial.all_testimonial',compact('testimonial'));
    }

    public function AddTestimonials(){
        return view('backend.testimonial.add_testimonial');
    }

    public function StoreTestimonials(Request $request){
        
        $image=$request->file('image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(100,100)->save('upload/testimonial/'.$name_gen);
        $save_img='upload/testimonial/'.$name_gen;

        Testimonial::insert([
        'name'=>$request->name,
        'position'=>$request->position,
        'message'=>$request->message,
        'image'=>$save_img,
    ]);
    $notification=array(
        'message'=>'Testimonial Inserted Successfully',
        'alert-type'=>'success'
    );
    return redirect()->route('all.testimonials')->with($notification);
    }

    public function EditTestimonials($id){

        $testimonial=Testimonial::findOrFail($id);
        return view('backend.testimonial.edit_testimonial',compact('testimonial'));
    }
    public function UpdateTestimonials(Request $request){

        $testimonial_id=$request->id;

if($request->file('image')){

        $image=$request->file('image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(100,100)->save('upload/testimonial/'.$name_gen);
        $save_img='upload/testimonial/'.$name_gen;

Testimonial::findOrFail($testimonial_id)->update([
    'name'=>$request->name,
    'position'=>$request->position,
    'message'=>$request->message,
    'image'=>$save_img,
]);

$notification=array(
    'message'=>'Testimonial Update with Image Successfully',
    'alert-type'=>'success'
);
return redirect()->route('all.testimonials')->with($notification);

        }else{
            Testimonial::findOrFail($testimonial_id)->update([
                'name'=>$request->name,
                'position'=>$request->position,
                'message'=>$request->message,
            ]);
            
            $notification=array(
                'message'=>'Testimonial Update Successfully',
                'alert-type'=>'success'
            );
            return redirect()->route('all.testimonials')->with($notification);
        }
    }

    public function DeleteTestimonials($id){

        $testimonial=Testimonial::findOrFail($id);
        $img=$testimonial->image;
        unlink($img);
       
        Testimonial::findOrFail($id)->delete();

        $notification=array(
            'message'=>'Testimonial Deleted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }
}
