<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\PropertyType;
use App\Models\Amenities;
use App\Models\User;
use App\Models\PropertyMessage;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PackagePlan;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function AllProperty(){

        $property=Property::latest()->get();
        return view('backend.property.all_property',compact('property'));
    }

    public function AddProperty(){
        $propertytype=PropertyType::latest()->get();
        $amenities=Amenities::latest()->get();
        $activeAgent=User::where('status','active')->where('role','agent')->latest()->get();
        return view('backend.property.add_property',compact('propertytype','amenities','activeAgent'));
    }

    public function StoreProperty(Request $request){

            $ameni=$request->amenities_id;
            $amenites=implode(",",$ameni);
            //dd($amenites);

            $pcode=IdGenerator::generate(['table'=>'properties','field'=>'property_code',
            'length'=>5,'prefix'=>'PC']);

        $image=$request->file('property_thambnail');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/property/thambnail/'.$name_gen);
        $save_url='upload/property/thambnail/'.$name_gen;

        $property_id = Property::insertGetId([

            'ptype_id'=>$request->ptype_id,
            'amenities_id'=>$amenites,
            'property_name'=>$request->property_name,
            'property_slug'=> strtolower(str_replace(' ','-',$request->property_name)),
            'property_code'=>$pcode,
            'property_status'=>$request->property_status,

            'lowest_price'=>$request->lowest_price,
            'max_price'=>$request->max_price,
            'short_descp'=>$request->short_descp,
            'long_descp'=>$request->long_descp,
            'bedrooms'=>$request->bedrooms,
            'bathrooms'=>$request->bathrooms,
            'garage'=>$request->garage,
            'garage_size'=>$request->garage_size,

            'property_size'=>$request->property_size,
            'property_video'=>$request->property_video,
            'address'=>$request->address,
            'city'=>$request->city,
            'state'=>$request->state,
            'postal_code'=>$request->postal_code,

            'neighborhood'=>$request->neighborhood,
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
            'featured'=>$request->featured,
            'hot'=>$request->hot,
            'agent_id'=>$request->agent_id,
            'status'=> 1,
            'property_thambnail'=>$save_url,
            'created_at'=>Carbon::now(),

        ]);

        //Multipole image upload from here

        $image=$request->file('multi_img');
        foreach($image as $img){
        $make_name=hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        Image::make($img)->resize(770,520)->save('upload/property/multi_image/'.$make_name);
        $uploadPath='upload/property/multi_image/'.$make_name;

        MultiImage::insert([
            'property_id'=>$property_id,
            'photo_name'=>$uploadPath,
            'created_at'=>Carbon::now(),
        ]);
     }//End Foreach
//End Multiple Image Upload Here

//Facilities Add From Here

     $facilities=Count($request->facility_name);

     if($facilities != NULL){
        for($i=0; $i<$facilities; $i++){
            $fcount=new Facility();
            $fcount->property_id=$property_id;
            $fcount->facility_name=$request->facility_name[$i];
            $fcount->distance=$request->distance[$i];
            $fcount->save();
        }
     }

     $notification = [
        'message' => 'Propertry Inserted Successfully',
        'alert-type' => 'success'
    ];

    return redirect()->route('all.property')->with($notification);


     }//End Method

     public function EditProperty($id){

        $facilities=Facility::where('property_id',$id)->get();
        $property=Property::findOrFail($id);

        $type=$property->amenities_id;
        $property_ame=explode(',',$type);

        $multImage = MultiImage::where('property_id',$id)->get();

        $propertytype=PropertyType::latest()->get();
        $amenities=Amenities::latest()->get();
        $activeAgent=User::where('status','active')->where('role','agent')->latest()->get();


        return view('backend.property.edit_property',
        compact('property','propertytype','amenities','activeAgent','property_ame','multImage','facilities'));
     }

     public function UpdateProperty(Request $request){

        $ameni=$request->amenities_id;
        $amenites=implode(",",$ameni);

        $property_id=$request->id;
        Property::findOrFail($property_id)->update([

            'ptype_id'=>$request->ptype_id,
            'amenities_id'=>$amenites,
            'property_name'=>$request->property_name,
            'property_slug'=> strtolower(str_replace(' ','-',$request->property_name)),
            'property_status'=>$request->property_status,
            'lowest_price'=>$request->lowest_price,
            'max_price'=>$request->max_price,
            'short_descp'=>$request->short_descp,
            'long_descp'=>$request->long_descp,
            'bedrooms'=>$request->bedrooms,
            'bathrooms'=>$request->bathrooms,
            'garage'=>$request->garage,
            'garage_size'=>$request->garage_size,

            'property_size'=>$request->property_size,
            'property_video'=>$request->property_video,
            'address'=>$request->address,
            'city'=>$request->city,
            'state'=>$request->state,
            'postal_code'=>$request->postal_code,

            'neighborhood'=>$request->neighborhood,
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
            'featured'=>$request->featured,
            'hot'=>$request->hot,
            'agent_id'=>$request->agent_id,
            'updated_at'=>Carbon::now(),

        ]);
        $notification = [
            'message' => 'Propertry Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.property')->with($notification);
     }

     public function UpdatePropertyThambnail(Request $request){

        $pro_id=$request->id;
        $oldImg=$request->old_img;

        $image=$request->file('property_thambnail');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/property/thambnail/'.$name_gen);
        $save_url='upload/property/thambnail/'.$name_gen;

        if(file_exists($oldImg)){
            unlink($oldImg);
        }

        Property::findOrFail($pro_id)->update([
            'property_thambnail'=>$save_url,
            'updated_at'=>Carbon::now(),
        ]);
        $notification = [
            'message' => 'Propertry Image Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
     }
        public function UpdatePropertyMultiImage(Request $request){

            $imgs=$request->multi_img;

            foreach ($imgs as $id => $img) {
                $imgDel=MultiImage::findOrFail($id);
                 unlink($imgDel->photo_name);

                // $imgPath=public_path($imgDel->photo_name);
                // if(file_exists($imgPath)){
                //     unlink($imgPath);
                // }

                $make_name=hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
                Image::make($img)->resize(770,520)->save('upload/property/multi_image/'.$make_name);
                $uploadPath='upload/property/multi_image/'.$make_name;

                MultiImage::where('id',$id)->update([
                    'photo_name'=>$uploadPath,
                    'updated_at'=>now(),
                ]);
            }
            $notification = [
                'message' => 'Propertry Multi-Image Updated Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->back()->with($notification);
    }
    public function PropertyMultiImgDelete($id){

        $oldImg=MultiImage::findOrFail($id);

        $imagePath=public_path($oldImg->photo_name);
        if(file_exists($imagePath)){
            unlink($imagePath);
        }
        MultiImage::findOrFail($id)->delete();
        $notification = [
         'message' => 'Propertry Multi-Image Deleted Successfully',
         'alert-type' => 'success'
     ];
     return redirect()->back()->with($notification);
     }

     public function StoreNewMultiImage(Request $request){

        $new_multi=$request->imageid;
        $image=$request->file('multi_img');
        

        $make_name=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(770,520)->save('upload/property/multi_image/'.$make_name);
        $uploadPath='upload/property/multi_image/'.$make_name;

        MultiImage::insert([
            'property_id'=>$new_multi,
            'photo_name'=>$uploadPath,
            'created_at'=>now(),
        ]);
        $notification = [
            'message' => 'Propertry Multi-Image Added Successfully Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
     }

     public function UpdatePropertyFacilities(Request $request){

        $pid=$request->id;
        if($request->facility_name==null){
            return redirect()->back();
        }else{
            Facility::where('property_id',$pid)->delete();
            $facilities=Count($request->facility_name);
                for($i=0; $i<$facilities;$i++){
                    $fcount=new Facility();
                    $fcount->property_id=$pid;
                    $fcount->facility_name=$request->facility_name[$i];
                    $fcount->distance=$request->distance[$i];
                    $fcount->save();
                }
        }
        $notification = [
            'message' => 'Propertry Facility Update Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
     }

     public function DeleteProperty($id){

        $property=Property::findOrFail($id);

        $unlink=public_path($property->property_thambnail);
        if(file_exists($unlink)){
            unlink($unlink);
        }
        
        Property::findOrFail($id)->delete();

        $image=MultiImage::where('property_id',$id)->get();
        foreach($image as $img){

            unlink($img->photo_name);

            MultiImage::findOrFail($id)->delete();
        }

        $facilities=Facility::where('property_id',$id)->get();
        foreach($facilities as $item){
            $item->facility_name;
            Facility::where('property_id',$id)->delete();
        }
        $notification = [
            'message' => 'Propertry  Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
     }

     public function DetailsProperty($id){
        $facilities=Facility::where('property_id',$id)->get();
        $property=Property::findOrFail($id);

        $type=$property->amenities_id;
        $property_ame=explode(',',$type);

        $multImage = MultiImage::where('property_id',$id)->get();

        $propertytype=PropertyType::latest()->get();
        $amenities=Amenities::latest()->get();
        $activeAgent=User::where('status','active')->where('role','agent')->latest()->get();


        return view('backend.property.details_property',
        compact('property','propertytype','amenities','activeAgent','property_ame','multImage','facilities'));
     }

     public function InActiveProperty(Request $request){

        $pid=$request->id;
        Property::findOrFail($pid)->update([
            'status'=>0,
        ]);
        $notification = [
            'message' => 'Propertry  InActive Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.property')->with($notification);
     }

     public function ActiveProperty(Request $request){
        $pid=$request->id;
        Property::findOrFail($pid)->update([
            'status'=>1,
        ]);
         $notification = [
            'message' => 'Propertry  Active Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.property')->with($notification);
     }

     public function AdminPackageHistory(){
    $packagehistory=PackagePlan::latest()->get();
    return view('backend.package.package_history',compact('packagehistory'));
     }

     public function PackageInvoice($id){
        $packagehistory=PackagePlan::where('id',$id)->first();
        $pdf=Pdf::loadView('backend.package.package_history_invoice',compact('packagehistory'))->
        setPaper('a4')->setOption([
            'tempDir'=>public_path(),
            'chroot'=>public_path(),
        ]);
        return $pdf->download('invoice.pdf');
     }

     public function AdminPropertyMessage(){
        $usermsg=PropertyMessage::latest()->get();
        return view('backend.message.all_massage',compact('usermsg'));
     }
     public function AdminMessageDetails($id){
        $uid=Auth::user()->id;
        $usermsg=PropertyMessage::where('agent_id',$uid)->get();

        $msgdetails=PropertyMessage::findOrFail($id);

        return view('backend.message.message_details',compact('msgdetails','usermsg'));
    }

}
