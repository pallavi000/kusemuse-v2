<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('check',[App\Http\Controllers\FrontendController::class,'check']);
    Route::get('checkguest/{id}',[App\Http\Controllers\FrontendController::class,'checkguest']);

    Route::get('profile',[App\Http\Controllers\CustomerController::class,'profile']);
    Route::post('address',[App\Http\Controllers\CustomerController::class,'address']);
    Route::get('editaddress',[App\Http\Controllers\CustomerController::class,'editaddress']);
    Route::post('replaceadd',[App\Http\Controllers\CustomerController::class,'replaceadd']);
    Route::post('payment',[App\Http\Controllers\CustomerController::class,'payment']);
    Route::get('order',[App\Http\Controllers\OrderController::class,'order']);
    Route::get('distroy/{id}',[App\Http\Controllers\CustomerController::class,'distroy']);
    Route::get('cartcount',[App\Http\Controllers\AddtocartController::class,'count']);
    Route::get('wishcount',[App\Http\Controllers\AddtocartController::class,'wishcount']);

    Route::post('addtocart',[App\Http\Controllers\AddtocartController::class,'cart']);
    Route::post('cartchange/{id}',[App\Http\Controllers\CustomerController::class,'changecart']);
    Route::post('addtowish',[App\Http\Controllers\WishlistController::class,'addtowish']);
    Route::get('wishlist',[App\Http\Controllers\WishlistController::class,'wishlist']);
    Route::get('buyers/{id}',[App\Http\Controllers\ProductController::class,'buyers']);
    Route::post('comment',[App\Http\Controllers\ReviewController::class,'comment']);
    Route::post('coupon',[App\Http\Controllers\CouponController::class,'coupon']);
    Route::post('paymentverify',[App\Http\Controllers\CustomerController::class,'paymentverify']);
    Route::post('/esewa-verification',[App\Http\Controllers\CustomerController::class,'esewaVerification']);
    Route::get('shipping',[App\Http\Controllers\ShippingController::class,'shipping']);
    Route::get('orderreceived/{id}',[App\Http\Controllers\CustomerController::class,'orderreceived']);

    Route::get('ordercancel/{id}',[App\Http\Controllers\OrderController::class,'ordercancel']);
    Route::post('changepassword',[App\Http\Controllers\UserController::class,'changepassword']);
    Route::get('cities',[App\Http\Controllers\CityController::class,'cities']);
    Route::get('country',[App\Http\Controllers\CityController::class,'country']);
    Route::get('change-shipping',[App\Http\Controllers\ShippingController::class,'changeShipping']);

});

Route::get('single-product/{id}',[App\Http\Controllers\ProductController::class,'singleProduct']);
Route::post('page-content',[App\Http\Controllers\PageController::class,'page']);
Route::post('subscription',[App\Http\Controllers\CustomerController::class,'subscription']);

Route::post('setpassword',[App\Http\Controllers\UserController::class,'setpassword']);
Route::post('verifyemail',[App\Http\Controllers\UserController::class,'verifyemail']);
Route::get('search/{slug}',[App\Http\Controllers\ProductController::class,'search']);
Route::post('searchfilter',[App\Http\Controllers\ProductController::class,'searchfilter']);

Route::get('banner', [App\Http\Controllers\FrontendController::class, 'banner']);


Route::get('product', [App\Http\Controllers\FrontendController::class, 'product']);
Route::post('reactlogin',[App\Http\Controllers\Auth\LoginController::class,'reactlogin']);
Route::get('detail/{id}',[App\Http\Controllers\ProductController::class,'detail']);
Route::get('category',[App\Http\Controllers\CategoryController::class,'category']);
Route::get('catdetail/{slug}',[App\Http\Controllers\CategoryController::class,'catdetail']);
Route::get('boutique', [App\Http\Controllers\BoutiqueController::class, 'boutique']);
Route::get('boutique', [App\Http\Controllers\BoutiqueController::class, 'boutique']);
Route::post('boutiquefilter',[App\Http\Controllers\BoutiqueController::class,'boutiquefilter']);

Route::get('designer/{slug}',[App\Http\Controllers\BoutiqueController::class,'designerdress']);
Route::post('catdesign',[App\Http\Controllers\BoutiqueController::class,'catdesign']);
Route::post('designcolor',[App\Http\Controllers\BoutiqueController::class,'designcolor']);
Route::post('boutiquecolor',[App\Http\Controllers\BoutiqueController::class,'boutiquecolor']);
Route::post('cateboutique',[App\Http\Controllers\BoutiqueController::class,'cateboutique']);
Route::post('googlelogin', [App\Http\Controllers\Auth\RegisterController::class, 'googlelogin']);


Route::get('category/product/{id}',[App\Http\Controllers\CategoryController::class,'catproduct']);
Route::post('signup',[App\Http\Controllers\Auth\RegisterController::class,'create']);
Route::get('branddetail/{slug}',[App\Http\Controllers\CategoryController::class,'branddetail']);
Route::get('colordetail/{slug}/{category_slug}',[App\Http\Controllers\CategoryController::class,'colordetail']);
Route::post('brandfetch',[App\Http\Controllers\BrandController::class,'brandfetch']);
Route::post('designerfetch',[App\Http\Controllers\BoutiqueController::class,'designerfetch']);

Route::post('/filter',[App\Http\Controllers\CategoryController::class,'filterFetch']);
Route::post('/brandfilter',[App\Http\Controllers\CategoryController::class,'brandfilter']);

Route::get('review/{id}',[App\Http\Controllers\ReviewController::class,'review']);
Route::get('categorypage/{slug}',[App\Http\Controllers\FrontendController::class,'categorypage']);
Route::get('brandpage/{slug}',[App\Http\Controllers\FrontendController::class,'brandpage']);


// guest-Login add to cart
Route::post('guestaddtocart',[App\Http\Controllers\AddtocartController::class,'guestaddtocart']);
Route::get('guestcount/{id}',[App\Http\Controllers\AddtocartController::class,'guestcount']);
Route::get('guestremove/{id}',[App\Http\Controllers\CustomerController::class,'guestremove']);
Route::post('cartadd/{id}',[App\Http\Controllers\CustomerController::class,'cartadd']);

Route::post('order-tracking',[App\Http\Controllers\OrderController::class,'orderTracking']);









