<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\PropertyType;
use App\Models\Amenities;
use App\Models\User;
use App\Models\PackagePlan;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AgentPropertyController extends Controller
{
    public function AgentAllProperty(){


        $id=Auth::user()->id;
        $property=Property::where('agent_id',$id)->latest()->get();
        return view('agent.property.all_property',compact('property'));
    }

    public function AgentAddProperty(){

        $propertytype=PropertyType::latest()->get();
        $amenities=Amenities::latest()->get();

        $id=Auth::user()->id;
        $property=User::where('role','agent')->where('id',$id)->first();

        $pcount=$property->credit;
        //dd($pcount);
        if($pcount==1 || $pcount==7){
            return redirect()->route('buy.package');
        }else{
            return view('agent.property.add_property',compact('propertytype','amenities'));
        }

        
    }
    public function AgentStoreProperty(Request $request){

        $id=Auth::user()->id;
        $uid=User::findOrFail($id);
        $nid=$uid->credit;

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
        'agent_id'=>Auth::user()->id,
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

 User::where('id',$id)->update([
    'credit'=>DB::raw('1+'.$nid),
 ]);

 $notification = [
    'message' => 'Propertry Inserted Successfully',
    'alert-type' => 'success'
];

return redirect()->route('agent.all.property')->with($notification);
    }

    public function AgentEditProperty($id){
        $facilities=Facility::where('property_id',$id)->get();
        $property=Property::findOrFail($id);

        $type=$property->amenities_id;
        $property_ame=explode(',',$type);

        $multImage = MultiImage::where('property_id',$id)->get();

        $propertytype=PropertyType::latest()->get();
        $amenities=Amenities::latest()->get();
        

        return view('agent.property.edit_property',
        compact('property','propertytype','amenities','property_ame','multImage','facilities'));
    }

    public function AgentUpdateProperty(Request $request){
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
            'agent_id'=>Auth::user()->id,
            'updated_at'=>Carbon::now(),

        ]);
        $notification = [
            'message' => 'Propertry Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('agent.all.property')->with($notification);
     }

     public function AgentUpdatePropertyThambnail(Request $request){

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
    public function AgentUpdatePropertyMultiImage(Request $request){

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
    public function AgentPropertyMultiImageDelete($id){

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
    public function AgentStoreNewMultiImage(Request $request){

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

    public function AgentUpdatePropertyFacilities(Request $request){

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

    public function AgentDetailsProperty($id){

        $facilities=Facility::where('property_id',$id)->get();
        $property=Property::findOrFail($id);

        $type=$property->amenities_id;
        $property_ame=explode(',',$type);

        $multImage = MultiImage::where('property_id',$id)->get();

        $propertytype=PropertyType::latest()->get();
        $amenities=Amenities::latest()->get();


        return view('agent.property.details_property',
        compact('property','propertytype','amenities','property_ame','multImage','facilities'));

    }

    public function AgentDeleteProperty($id){

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

    public function BuyPackage(){
        return view('agent.package.buy_package');
    }

    public function BuyBusinessPlan(){
        $id=Auth::user()->id;
        $data=User::find($id);
        return view('agent.package.business_plan',compact('data'));
    }
    public function StoreBusinessPlan(Request $request){

        $id=Auth::user()->id;

        $uid=User::findOrFail($id);
        $nid=$uid->credit;

        PackagePlan::insert([
            'user_id'=>$id,
            'package_name'=>'Business',
            'package_credits'=>'3',
            'invoice'=>'ERS'.mt_rand(10000000,99999999),
            'package_amount'=>'20',
            'created_at'=>now(),
        ]);

        User::where('id',$id)->update([
            'credit'=>DB::raw('3 + '.$nid),
        ]);

        $notification = [
            'message' => 'You have purchase Basic Package Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('agent.all.property')->with($notification);
    }

    public function BuyProfessionalPlan(){
        $id=Auth::user()->id;
        $data=User::find($id);
        return view('agent.package.professiona_plan',compact('data'));
    }

    public function StoreProfessionalPlan(Request $request){
        $id=Auth::user()->id;

        $uid=User::findOrFail($id);
        $nid=$uid->credit;

        PackagePlan::insert([
            'user_id'=>$id,
            'package_name'=>'Professional',
            'package_credits'=>'10',
            'invoice'=>'ERS'.mt_rand(10000000,99999999),
            'package_amount'=>'50',
            'created_at'=>now(),
        ]);

        User::where('id',$id)->update([
            'credit'=>DB::raw('10 + '.$nid),
        ]);

        $notification = [
            'message' => 'You have purchase Professional Package Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('agent.all.property')->with($notification);
    }

    public function PackageHistory(){
        $id=Auth::user()->id;
        $packagehistory=PackagePlan::where('user_id',$id)->get();
        return view('agent.package.package_history',compact('packagehistory'));

    }

    public function AgentPackageInvoice($id){

        $packagehistory=PackagePlan::where('id',$id)->first();

        $pdf=Pdf::loadView('agent.package.package_history_invoice',
        compact('packagehistory'))->setPaper('a4')->setOption([
            'tempDir'=>public_path(),
            'chroot'=>public_path(),
        ]);
        return $pdf->download('invoice.pdf');

    }
}
