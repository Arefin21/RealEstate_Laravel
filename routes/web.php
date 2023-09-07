<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\PropertyTypeController;
use App\Http\Controllers\Backend\PropertyController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\RoleController;

use App\Http\Controllers\Agent\AgentPropertyController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CompareController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfAuthenticated;



// Route::get('/', function () {
//     return view('welcome');
// });

//User Frontend All Route
Route::get('/',[UserController::class,'index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user/profile', [UserController::class, 'UserProfile'])->name('user.profile');
    
    Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
    
    Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');

    Route::get('/user/change/password', [UserController::class, 'UserChangePassword'])->name('user.change.password');

    Route::post('/user/password/update',[UserController::class,'UserPasswordUpdate'])->name('user.password.update');

    Route::get('/user/schedule/request',[UserController::class,'UseruserScheduleRequest'])->name('user.schedule.request');

    //User Wishlist all route

    Route::controller(WishlistController::class)->group(function(){

      Route::get('/user/wishlist','UserWishlist')->name('user.wishlist');
      Route::get('/get-wishlist-property','GetWishlistProperty');
      Route::get('/wishlist-remove/{id}','WishlistRemove');


    });
    //User Comare all route
    Route::controller(CompareController::class)->group(function(){

    
      Route::get('/user/compare','UserCompare')->name('user.compare');

      Route::get('/get-compare-property','GetCompareProperty');

      Route::get('/compare-remove/{id}','CompareRemove');


    });
    
});

require __DIR__.'/auth.php';

//Admin Group Middleware

Route::middleware('auth','role:admin')->group(function () {
Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');

Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');

Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');

Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');

Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');

Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');
});


//Agent Group Middleware--------------------



Route::middleware('auth','role:agent')->group(function(){
    Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])
    ->name('agent.dashboard');

    Route::get('/agent/logout', [AgentController::class, 'AgentLogout'])
    ->name('agent.logout');
    
    Route::get('/agent/profile', [AgentController::class, 'AgentProfile'])->name('agent.profile');

    Route::post('/agent/profile/store', [AgentController::class, 'AgentProfileStore'])->name('agent.profile.store');

    Route::get('/agent/change/password', [AgentController::class, 'AgentChangePassword'])->name('agent.change.password');

Route::post('/agent/update/password', [AgentController::class, 'AgentUpdatePassword'])->name('agent.update.password');

    
});


Route::get('/agent/login', [AgentController::class, 'AgentLogin'])->name('agent.login')
->middleware(RedirectIfAuthenticated :: Class);

Route::post('/agent/register', [AgentController::class, 'AgentRegister'])->name('agent.register')
->middleware(RedirectIfAuthenticated :: Class);



//Admin
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login')
->middleware(RedirectIfAuthenticated :: Class);


Route::middleware('auth','role:admin')->group(function () {
 
//  Paroperty Type All Route  
Route::controller(PropertyTypeController::class)->group(function(){

    Route::get('/all/type','AllType')->name('all.type');
    Route::get('/add/type','AddType')->name('add.type');
    Route::post('/store/type','StoreType')->name('store.type');
    Route::get('/edit/type{id}','EditType')->name('edit.type');
    Route::post('/update/type','UpdateType')->name('update.type');
    Route::get('/delete/type{id}','DeleteType')->name('delete.type');


  });

  //  Amenities Type All Route  
Route::controller(PropertyTypeController::class)->group(function(){

    Route::get('/all/amenitie','AllAmenitie')->name('all.amenitie');
    Route::get('/add/amenitie','AddAmenitie')->name('add.amenitie');
    Route::post('/store/amenitie','StoreAmenitie')->name('store.aminitie');
    Route::get('/edit/amenitie{id}','EditAmenitie')->name('edit.aminitie');
    Route::post('/update/amenitie','UpdateAmenitie')->name('update.aminitie');
    Route::get('/delete/amenitie{id}','DeleteAmenitie')->name('delete.aminitie');


  });

  //Property All Route

  Route::controller(PropertyController::class)->group(function(){

    Route::get('/all/property','AllProperty')->name('all.property');
    Route::get('/add/property','AddProperty')->name('add.property');
    Route::post('/store/property','StoreProperty')->name('store.property');
    Route::get('/edit/property/{id}','EditProperty')->name('edit.property');
    Route::post('/update/property','UpdateProperty')->name('update.property');
    Route::post('/update/property/thambnail','UpdatePropertyThambnail')->name('update.property.thambnail');
    Route::post('/update/property/multiImage','UpdatePropertyMultiImage')->name('update.property.multiImage');
    Route::get('/property/multiimg/delete/{id}','PropertyMultiImgDelete')->name('property.multiimg.delete');
    Route::post('/store/new/multiImage/','StoreNewMultiImage')->name('store.new.multiImage');
    Route::post('/update/property/facilities/','UpdatePropertyFacilities')->name('update.property.facilities');
    Route::get('/delete/property/{id}','DeleteProperty')->name('delete.property');
    Route::get('/details/property/{id}','DetailsProperty')->name('details.property');
    Route::post('/inactive/property','InActiveProperty')->name('inactive.property');
    Route::post('/active/property','ActiveProperty')->name('active.property');
    Route::get('/admin/package/history','AdminPackageHistory')->name('admin.package.history');
    Route::get('/package/invoice/{id}','PackageInvoice')->name('package.invoice');
    Route::get('/admin/property/message/','AdminPropertyMessage')->name('admin.property.message');
    Route::get('/admin/message/details/{id}','AdminMessageDetails')->name('admin.message.details');


  });

  //Agent All Route

  Route::controller(AdminController::class)->group(function(){

    Route::get('/all/agent','AllAgent')->name('all.agent');
    Route::get('/add/agent','AddAgent')->name('add.agent');
    Route::post('/store/agent','StoreAgent')->name('store.agent');
    Route::get('/edit/agent/{id}','EditAgent')->name('edit.agent');
    Route::get('/update/agent','UpdateAgent')->name('update.agent');
    Route::get('/delete/agent/{id}','DeleteAgent')->name('delete.agent');

    Route::get('/changeStatus','changeStatus');

  });

  //State All Route

  Route::controller(StateController::class)->group(function(){

    
    Route::get('/all/state','AllState')->name('all.state');

    Route::get('/add/state','AddState')->name('add.state');

    Route::post('/store/state','StoreState')->name('store.state');

    Route::get('/edit/state/{id}','EditState')->name('edit.state');

    Route::post('/update/state','UpdateState')->name('update.state');

    Route::get('/delete/state/{id}','DeleteState')->name('delete.state');
    

  });

  //TestimonialController All Route

  Route::controller(TestimonialController::class)->group(function(){

    Route::get('/all/testimonials','AllTestimonials')->name('all.testimonials');
    Route::get('/add/testimonials','AddTestimonials')->name('add.testimonials');
    Route::post('/store/testimonials','StoreTestimonials')->name('store.testimonials');
    Route::get('/edit/testimonials/{id}','EditTestimonials')->name('edit.testimonials');
    Route::post('/update/testimonials','UpdateTestimonials')->name('update.testimonials');
    Route::get('/delete/testimonials/{id}','DeleteTestimonials')->name('delete.testimonials');


  });

  //Blog Category all route

  Route::controller(BlogController::class)->group(function(){

    Route::get('/all/blog/category','AllBlogCategory')->name('all.blog.category');
    Route::post('/store/blog/category','StoreBlogCategory')->name('store.blog.category');
    Route::get('/blog/category/{id}','EditBlogCategory');
    Route::post('/update/blog/category','UpdateBlogCategory')->name('update.blog.category');
    Route::get('/delete/blog/category{id}','DeleteBlogCategory')->name('delete.blog.category');


  });

  //Blog Post All Route

  Route::controller(BlogController::class)->group(function(){

    Route::get('/all/post','AllPost')->name('all.post');
    Route::get('/add/post','AddPost')->name('add.post');
    Route::post('/store/post','StorePost')->name('store.post');
    Route::get('/edit/post/{id}','EditPost')->name('edit.post');
    Route::post('/update/post','UpdatePost')->name('update.post');
    Route::get('/delete/post{id}','DeletePost')->name('delete.post');


  });

  //SMTP Setting Route

  Route::controller(SettingController::class)->group(function(){

    Route::get('/smtp/setting','SmtpSetting')->name('smtp.setting');
    Route::post('/update/smtp/setting','UpdateSmtpSetting')->name('update.smtp.setting');
    Route::get('/site/setting','SiteSetting')->name('site.setting');
    Route::post('/update/site/setting','UpdateSiteSetting')->name('update.site.setting');
   


  });

  // Permission All Route

  Route::controller(RoleController::class)->group(function(){

    Route::get('/all/permission','AllPermission')->name('all.permission');
    Route::get('/add/permission','AddPermission')->name('add.permission');
    Route::post('/store/permission','Storepermission')->name('store.permission');
    Route::get('/edit/permission{id}','Editpermission')->name('edit.permission');
    Route::post('/update/permission','Updatepermission')->name('update.permission');
    Route::get('/delete/permission{id}','Deletepermission')->name('delete.permission');



 });

});

 ///Agent Group Middleware
 Route::middleware(['auth','role:agent'])->group(function(){

  Route::controller(AgentPropertyController::class)->group(function(){

    Route::get('/agent/all/property','AgentAllProperty')->name('agent.all.property');
    Route::get('/agent/add/property','AgentAddProperty')->name('agent.add.property');
    Route::post('/agent/store/property','AgentStoreProperty')->name('agent.store.property');
    Route::get('/agent/edit/property/{id}','AgentEditProperty')->name('agent.edit.property');
    Route::post('/agent/update/property','AgentUpdateProperty')->name('agent.update.property');
    Route::post('/agent/update/property/thambnail','AgentUpdatePropertyThambnail')->name('agent.update.property.thambnail');
    Route::post('/agent/update/property/multiImage','AgentUpdatePropertyMultiImage')->name('agent.update.property.multiImage');
    Route::get('/agent/property/multiimg/delete/{id}','AgentPropertyMultiImageDelete')->name('agent.property.multiimg.delete');
    Route::post('/agent/store/new/multiImage/','AgentStoreNewMultiImage')->name('agent.store.new.multiImage');
    Route::post('/agent/update/property/facilities/','AgentUpdatePropertyFacilities')->name('agent.update.property.facilities');
    Route::get('/agent/details/property/{id}','AgentDetailsProperty')->name('agent.details.property');
    Route::get('/agent/delete/property/{id}','AgentDeleteProperty')->name('agent.delete.property');
    Route::get('/agent/property/message/','AgentPropertyMessage')->name('agent.property.message');
    Route::get('/agent/message/details/{id}','AgentMessageDetails')->name('agent.message.details');
//Schedule Request Route
    Route::get('/agent/schedule/request','AgentScheduleRequest')->name('agent.schedule.request');

    Route::get('/agent/details/schedule/{id}','AgentDetailsSchedule')->name('agent.details.schedule');

    Route::post('/agent/update/schedule/','AgentUpdateSchedule')->name('agent.update.schedule');

    
  });

  //Agent Buy Package route


    Route::controller(AgentPropertyController::class)->group(function(){

      Route::get('/buy/package','BuyPackage')->name('buy.package');
      Route::get('/buy/business/plan','BuyBusinessPlan')->name('buy.business.plan');
      Route::post('/store/business/plan','StoreBusinessPlan')->name('store.business.plan');
      Route::get('/buy/professional/plan','BuyProfessionalPlan')->name('buy.professional.plan');
      Route::post('/store/professional/plan','StoreProfessionalPlan')->name('store.professional.plan');
      Route::get('/package/history','PackageHistory')->name('package.history');
      Route::get('/agent/package/invoice/{id}','AgentPackageInvoice')->name('agent.package.invoice');


    });


 });
Route::get('/property/details/{id}/{slug}',[IndexController::class,'PropertyDetails']);

//WishList Add Route

Route::post('/add-to-wishList/{property_id}',[WishlistController::class,'AddToWishList']);

//Compare Add Route

Route::post('/add-to-compare/{property_id}',[CompareController::class,'AddToCompare']);

//Send Message from Property Details Page

Route::post('/property/message/',[IndexController::class,'PropertyMessage'])->name('property.message');

//Agent details page in frontend
Route::get('/agent/details/{id}',[IndexController::class,'AgentDetails'])->name('agent.details');

//Send Message from Agent Details Page
Route::post('/agent/details/message',[IndexController::class,'AgentDetailsMessage'])->name('agent.details.message');

//Get All Rent/Buy Property

Route::get('/rent/property',[IndexController::class,'RentProperty'])->name('rent.property');

Route::get('/buy/property',[IndexController::class,'BuyProperty'])->name('buy.property');

//Get all property type data

Route::get('/property/type/{id}',[IndexController::class,'PropertyType'])->name('property.type');


//Get State Details Data

Route::get('/state/details/{id}',[IndexController::class,'StateDetails'])->name('state.details');

//Home page search buy

Route::post('/buy/property/search',[IndexController::class,'BuyPropertySearch'])->name('buy.property.search');

Route::post('/rent/property/search',[IndexController::class,'RentPropertySearch'])->name('rent.property.search');

Route::post('/all/property/search',[IndexController::class,'AllPropertySearch'])->name('all.property.search');


//Blog Details

Route::get('/blog/details/{slug}',[BlogController::class,'BlogDetails']);
Route::get('/blog/cat/list/{id}',[BlogController::class,'BlogCatList']);
Route::get('/blog',[BlogController::class,'BlogList'])->name('blog.list');
Route::post('/store/comment',[BlogController::class,'StoreComment'])->name('store.comment');
Route::get('/admin/blog/comment',[BlogController::class,'AdminBlogComment'])->name('admin.blog.comment');
Route::get('/admin/comment/reply/{id}',[BlogController::class,'AdminCommentReply'])->name('admin.comment.reply');
Route::post('/reply.message',[BlogController::class,'ReplyMessage'])->name('reply.message');

//Schedule tour

Route::post('/store/schedule',[IndexController::class,'StoreSchedule'])->name('store.schedule');
