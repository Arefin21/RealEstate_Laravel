<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
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

    //For Blog

    public function AllPost(){
        $post=BlogPost::latest()->get();
        return view('backend.post.all_post',compact('post'));
    }

    public function AddPost(){
        $blogcat=BlogCategory::latest()->get();
        return view('backend.post.add_post',compact('blogcat'));
    }

    public function StorePost(Request $request){

        $image=$request->file('post_image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/post/'.$name_gen);
        $save_img='upload/post/'.$name_gen;

        BlogPost::insert([

            'blogcat_id'=>$request->blogcat_id,
            'user_id'=>Auth::user()->id,
            'post_title'=>$request->post_title,
            'post_slug'=>strtolower(str_replace(' ','-',$request->post_title)),
            'short_descp'=>$request->short_descp,
            'long_descp'=>$request->long_descp,
            'post_tags'=>$request->post_tags,
            'post_image'=>$save_img,
            'created_at'=>now(),
        ]);
        $notification=array(
            'message'=>'Blog Post Inserted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.post')->with($notification);
    }
    public function EditPost($id){

        $blogcat=BlogCategory::latest()->get();
        $post=BlogPost::findOrFail($id);
        return view('backend.post.edit_post',compact('post','blogcat'));
    }

    public function UpdatePost(Request $request){
        $post_id=$request->id;

        if($image=$request->file('post_image')){
        $image=$request->file('post_image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/post/'.$name_gen);
        $save_img='upload/post/'.$name_gen;

        BlogPost::findOrFail($post_id)->update([

            'blogcat_id'=>$request->blogcat_id,
            'user_id'=>Auth::user()->id,
            'post_title'=>$request->post_title,
            'post_slug'=>strtolower(str_replace(' ','-',$request->post_title)),
            'short_descp'=>$request->short_descp,
            'long_descp'=>$request->long_descp,
            'post_tags'=>$request->post_tags,
            'post_image'=>$save_img,
            'created_at'=>now(),
        ]);
        $notification=array(
            'message'=>'Blog Post Updated Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.post')->with($notification);

        }else{

            BlogPost::findOrFail($post_id)->update([

                'blogcat_id'=>$request->blogcat_id,
                'user_id'=>Auth::user()->id,
                'post_title'=>$request->post_title,
                'post_slug'=>strtolower(str_replace(' ','-',$request->post_title)),
                'short_descp'=>$request->short_descp,
                'long_descp'=>$request->long_descp,
                'post_tags'=>$request->post_tags,
                'created_at'=>now(),
            ]);
            $notification=array(
                'message'=>'Blog Post Updated Successfully',
                'alert-type'=>'success'
            );
            return redirect()->route('all.post')->with($notification);
        }
    }
    public function DeletePost($id){
        $post=BlogPost::findOrFail($id);
        $img=$post->post_image;
        unlink($img);

        BlogPost::findOrFail($id)->delete();

        $notification=array(
            'message'=>'Blog Post Deleted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }

    public function BlogDetails($slug){

        $blog=BlogPost::where('post_slug',$slug)->first();
        $tags=$blog->post_tags;
        $all_tags=explode(',',$tags);
        $bcategory=BlogCategory::latest()->get();
        $dpost=BlogPost::latest()->limit(3)->get();

        return view('frontend.blog.blog_details',compact('blog','all_tags','bcategory','dpost'));
    }

    public function BlogCatList($id){

        $blog=BlogPost::where('blogcat_id',$id)->get();
        $cname=BlogCategory::where('id',$id)->first();
        $bcategory=BlogCategory::latest()->get();
        $dpost=BlogPost::latest()->limit(3)->get();
        
        return view('frontend.blog.blog_cat_list',compact('blog','cname','bcategory','dpost'));
    }

    public function BlogList(){

        $blog=BlogPost::latest()->get();
        $bcategory=BlogCategory::latest()->get();
        $dpost=BlogPost::latest()->limit(3)->get();
        
        return view('frontend.blog.blog_list',compact('blog','bcategory','dpost'));
    }

    public function StoreComment(Request $request){
        $pid=$request->post_id;
        Comment::insert([
            'user_id'=>Auth::user()->id,
            'post_id'=>$pid,
            'parent_id'=>null,
            'subject'=>$request->subject,
            'message'=>$request->message,
            'created_at'=>now(),
        ]);
        $notification=array(
            'message'=>'Comment Inserted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }

    public function AdminBlogComment(){

        $comment=Comment::where('parent_id',null)->latest()->get();
        return view('backend.comment.comment_all',compact('comment'));

    }

    public function AdminCommentReply($id){

        $comment=Comment::where('id',$id)->first();
        return view('backend.comment.reply_comment',compact('comment'));

    }

    public function ReplyMessage(Request $request){

        $id=$request->id;
        $user_id=$request->user_id;
        $post_id=$request->post_id;

        Comment::insert([
            'user_id'=>$user_id,
            'post_id'=>$post_id,
            'parent_id'=>$id,
            'subject'=>$request->subject,
            'message'=>$request->message,
            'created_at'=>now(),
        ]);
        $notification=array(
            'message'=>'Comment Reply Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);

    }

}
