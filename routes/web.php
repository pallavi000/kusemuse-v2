<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\MailController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('admin/login', function(){
//     return view('auth.login');
// })->name('admin.login');

Route::get('/test',[App\Http\Controllers\ProductController::class,'test']);

Route::get('/send-email',[App\Http\Controllers\MailController::class,'sendEmail'])->name('emails.TestMail');
Route::get('/test-email',[App\Http\Controllers\MailController::class,'testemail'])->name('emails.TestMail');


Auth::routes();

//seller dashboard
Route::group(['middleware' => is_seller::class], function () {
    Route::get('/seller/order/invoice/{id}',[App\Http\Controllers\OrderController::class,'invoice'])->name('seller.order.invoice');
    Route::get('/seller/dashboard', [App\Http\Controllers\DashboardController::class,'adminHome'])->name('seller.dashboard');
    Route::resource('/seller/product', App\Http\Controllers\ProductController::class)->name('*','seller.product');
    Route::resource('/seller/order', App\Http\Controllers\OrderController::class)->name('*','seller.order');
    Route::resource('/seller/coupon', App\Http\Controllers\CouponController::class)->name('*','seller.coupon');
    Route::resource('/seller/boutique', App\Http\Controllers\BoutiqueController::class)->name('*','seller.boutique');
    Route::resource('/seller/customer', App\Http\Controllers\CustomerController::class)->name('*','seller.customer');
    Route::resource('/seller/withdraw', App\Http\Controllers\WithdrawController::class)->name('*','seller.withdraw');


});

//Admin Dashboard
Route::group(['middleware' => is_admin::class], function () {
    Route::get('/admin/order/invoice/{id}',[App\Http\Controllers\OrderController::class,'invoice'])->name('admin.order.invoice');
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class,'adminHome'])->name('admin.dashboard');
    Route::resource('admin/product', App\Http\Controllers\ProductController::class,  ['as'=>'admin']);
    Route::resource('admin/order', App\Http\Controllers\OrderController::class,['as'=>'admin']);
    Route::resource('admin/coupon', App\Http\Controllers\CouponController::class,['as'=>'admin']);
    Route::resource('admin/boutique', App\Http\Controllers\BoutiqueController::class,['as'=>'admin']);
    Route::resource('/admin/users', App\Http\Controllers\UserController::class)->name('*','admin.users');
    Route::resource('/admin/category', App\Http\Controllers\CategoryController::class)->name('*','admin.category');
    Route::resource('/admin/banner',App\Http\Controllers\BannerController::class)->name('*','admin.bannner');
    Route::resource('/admin/addtocart', App\Http\Controllers\AddtocartController::class)->name('*','admin.addtocart');
    Route::resource('/admin/size', App\Http\Controllers\SizeController::class)->name('*','admin.size');
    Route::resource('/admin/color', App\Http\Controllers\ColorController::class)->name('*','admin.color');
    Route::resource('/admin/customer', App\Http\Controllers\CustomerController::class,['as'=>'admin']);
    Route::resource('/admin/shipping', App\Http\Controllers\ShippingController::class)->name('*','admin.shipping');
    Route::resource('/admin/brand', App\Http\Controllers\BrandController::class)->name('*','admin.brand');
    Route::resource('/admin/designer', App\Http\Controllers\DesignerController::class)->name('*','admin.designer');
    Route::resource('/admin/country', App\Http\Controllers\CountryController::class)->name('*','admin.country');
    Route::resource('/admin/city', App\Http\Controllers\CityController::class)->name('*','admin.city');
    Route::resource('/admin/review', App\Http\Controllers\ReviewController::class)->name('*','admin.review');
    Route::resource('/admin/withdraw', App\Http\Controllers\WithdrawController::class,['as'=>'admin']);


    Route::resource('/admin/categorybanner', App\Http\Controllers\CategoryBannerController::class)->name('*','admin.categorybanner');
    Route::resource('/admin/featureblock', App\Http\Controllers\FeatureblockController::class)->name('*','admin.featureblock');
    Route::resource('/admin/page',App\Http\Controllers\PageController::class)->name('*','admin.page');



});


// User Panel
Route::get('/{path}', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->where('path','.*');
Auth::routes();


// Route::get('/logout', function(){
//    Auth::logout();
//    return Redirect::to('login');
// })->name('logout');

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



