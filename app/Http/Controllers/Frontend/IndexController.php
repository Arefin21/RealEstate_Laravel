<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\PropertyType;
use App\Models\Amenities;
use App\Models\User;
use App\Models\State;
use App\Models\PackagePlan;
use App\Models\PropertyMessage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function PropertyDetails($id,$slug){
        $property=Property::findOrFail($id);

        $amenities=$property->amenities_id;
        $property_amen=explode(',',$amenities);

        $multiImage=MultiImage::where('property_id',$id)->get();
        $facility=Facility::where('property_id',$id)->get();

        $type_id=$property->ptype_id;
        $relatedProperty=Property::where('ptype_id',$type_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(3)->get();

        return view('frontend.property.property_details',compact('property','multiImage','property_amen','facility','relatedProperty'));
    }
    public function PropertyMessage(Request $request){
        $pid=$request->property_id;
        $aid=$request->agent_id;

        if(Auth::check()){

            PropertyMessage::insert([
                'user_id'=>Auth::user()->id,
                'agent_id'=>$aid,
                'property_id'=>$pid,
                'msg_name'=>$request->msg_name,
                'msg_email'=>$request->msg_email,
                'msg_phone'=>$request->msg_phone,
                'message'=>$request->message,
                'created_at'=>now(),
            ]);
            $notification=array(
                'message'=>'Send Message Successfully',
                'alert-type'=>'success'
            );
            return redirect()->back()->with($notification);

        }else{
            $notification=array(
                'message'=>'Please LogIn your acccount first',
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function AgentDetails($id){

        $agent=User::findOrFail($id);
        $property=Property::where('agent_id',$id)->get();
        $featured=Property::where('featured','1')->limit(3)->get();

        $rentproperty=Property::where('property_status','rent')->get();
        $buyproperty=Property::where('property_status','buy')->get();

        return view('frontend.agent.agent_details',compact('agent','property','featured','rentproperty','buyproperty'));
    }

    public function AgentDetailsMessage(Request $request){
    
        $aid=$request->agent_id;

        if(Auth::check()){

            PropertyMessage::insert([
                'user_id'=>Auth::user()->id,
                'agent_id'=>$aid,
                'msg_name'=>$request->msg_name,
                'msg_email'=>$request->msg_email,
                'msg_phone'=>$request->msg_phone,
                'message'=>$request->message,
                'created_at'=>now(),
            ]);
            $notification=array(
                'message'=>'Send Message Successfully',
                'alert-type'=>'success'
            );
            return redirect()->back()->with($notification);

        }else{
            $notification=array(
                'message'=>'Please LogIn your acccount first',
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function RentProperty(){
        $property=Property::where('status','1')->where('property_status','rent')->paginate(2);

        $rentproperty=Property::where('property_status','rent')->get();
        $buyproperty=Property::where('property_status','buy')->get();
        return view('frontend.property.rent_property',compact('property','rentproperty','buyproperty'));
    }

    public function BuyProperty(){
        $property=Property::where('status','1')->where('property_status','buy')->paginate(2);
        $rentproperty=Property::where('property_status','rent')->get();
        $buyproperty=Property::where('property_status','buy')->get();
        return view('frontend.property.buy_property',compact('property','buyproperty','rentproperty'));  
    }

    public function PropertyType($id){
        $property=Property::where('status','1')->where('ptype_id',$id)->get();
        $pbread=PropertyType::where('id',$id)->first();
        return view('frontend.property.property_type',compact('property','pbread'));
    }

    public function StateDetails($id){
        $property=Property::where('status','1')->where('state',$id)->get();
        $bstate=State::where('id',$id)->first();
        return view('frontend.property.state_property',compact('property','bstate'));
    }

    public function BuyPropertySearch(Request $request){

        $request->validate(['search'=>'required']);

            $item=$request->search;
            $sstate=$request->state;
            $stype=$request->ptype_id;

            $property=Property::where('property_name','like','%'.$item.'%')->
                where('property_status','buy')->with('type','pstate')->
                whereHas('pstate',function($q) use($sstate){
                    $q->where('state_name','like','%'.$sstate.'%');
                })
                ->whereHas('type',function($q) use($stype){
                    $q->where('type_name','like','%'.$stype.'%');
                })->get();

        $rentproperty=Property::where('property_status','rent')->get();
        $buyproperty=Property::where('property_status','buy')->get();
        $propertyPagination=Property::where('status','1')->where('property_status','buy')->paginate(2);

        return view('frontend.property.property_search',compact('property','rentproperty','buyproperty','propertyPagination'));
    }

    public function RentPropertySearch(Request $request){
        $request->validate(['search'=>'required']);

        $item=$request->search;
        $sstate=$request->state;
        $stype=$request->ptype_id;

        $property=Property::where('property_name','like','%'.$item.'%')->
            where('property_status','rent')->with('type','pstate')->
            whereHas('pstate',function($q) use($sstate){
                $q->where('state_name','like','%'.$sstate.'%');
            })
            ->whereHas('type',function($q) use($stype){
                $q->where('type_name','like','%'.$stype.'%');
            })->get();

            $rentproperty=Property::where('property_status','rent')->get();
            $buyproperty=Property::where('property_status','buy')->get();
            $propertyPagination=Property::where('status','1')->where('property_status','rent')->paginate(2);
    
            return view('frontend.property.property_search',compact('property','rentproperty','buyproperty','propertyPagination'));
        }

        public function AllPropertySearch(Request $request){
           
            $property_status=$request->property_status;
            $sstate=$request->state;
            $stype=$request->ptype_id;
            $bedrooms=$request->bedrooms;
            $bathrooms=$request->bathrooms;

$property=Property::where('status','1')->where('bedrooms',$bedrooms)
            ->where('bathrooms','like','%'.$bathrooms.'%')
                ->where('property_status',$property_status)->with('type','pstate')
                    ->whereHas('pstate',function($q) use($sstate){
                        $q->where('state_name','like','%'.$sstate.'%');
                })
                ->whereHas('type',function($q) use($stype){
                    $q->where('type_name','like','%'.$stype.'%');
                })->get();
    
                $rentproperty=Property::where('property_status','rent')->get();
                $buyproperty=Property::where('property_status','buy')->get();
                $propertyPagination=Property::where('status','1')->where('property_status','rent')->paginate(2);
        
                return view('frontend.property.property_search',compact('property','rentproperty','buyproperty','propertyPagination'));
        }
}
