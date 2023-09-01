<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function AllBlogCategory(){
        $category=BlogCategory::latest()->get();
        return view('backend.category.blog_category',compact('category'));
    }

    public function StoreBlogCategory(Request $request){

        BlogCategory::insert([
            'catagory_name'=>$request->catagory_name,
            'catagory_slug'=>strtolower(str_replace(' ','-',$request->catagory_name)),
        ]);

        $notification=array(
            'message'=>'Blog Catagory Insert Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.blog.category')->with($notification);
    }

    public function EditBlogCategory($id){
        $category=BlogCategory::findOrFail($id);
        return response()->json($category);
    }

    public function UpdateBlogCategory(Request $request){

        $category_id=$request->cat_id;

        BlogCategory::findOrFail( $category_id)->update([
            'catagory_name'=>$request->catagory_name,
            'catagory_slug'=>strtolower(str_replace(' ','-',$request->catagory_name)),
        ]);

        $notification=array(
            'message'=>'Blog Catagory Updated Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.blog.category')->with($notification);
    }

    public function DeleteBlogCategory($id){
        

        BlogCategory::findOrFail($id)->delete();
   
    $notification=array(
        'message'=>'Blog Catagory Deleted Successfully',
        'alert-type'=>'success'
    );
    return redirect()->back()->with($notification);
    
    }
}
