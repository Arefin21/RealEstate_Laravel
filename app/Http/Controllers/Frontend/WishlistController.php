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
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WishlistController extends Controller
{
    public function AddToWishList(Request $request,$property_id){
        if(Auth::check()){
            $exists=Wishlist::where('user_id',Auth::id())->where('property_id',$property_id)
            ->first();
            if(!$exists){
                Wishlist::insert([
                    'user_id'=>Auth::id(),
                    'property_id'=>$property_id,
                    'created_at'=>now()
                ]);
                return response()->json(['success'=>'Successfullly Added On Your Wishlist']);
            }else{
                return response()->json(['error'=>'This Property has alreday in your wishlist']);
            }
        }else{
            return response()->json(['error'=>'At first login your account']);
        }
    }

    public function UserWishlist(){
        $id=Auth::user()->id;
        $userData=User::find($id);

        return view('frontend.dashboard.wishlist',compact('userData'));
    }

    public function GetWishlistProperty(){
        $wishlist=Wishlist::with('property')->where('user_id',Auth::id())->latest()->get();
        $wishQty=wishlist::count();
        return response()->json(['wishlist'=>$wishlist,'wishQty'=>$wishQty]);
    }

    public function WishlistRemove($id){
        Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
        return response()->json(['success'=>'Successfully Property Remove']);
    }
}
