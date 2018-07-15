<?php

use Illuminate\Http\Request;

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
Route::get('category','CategroryController@GetCategroy')->middleware('apilang');
Route::get('payment','PaymentController@getPayment')->middleware('apilang');
Route::get('getproductsort/{parameter}/{sort}/{CatID}','CategroryController@SortClassicProduct')->middleware('apilang');
Route::get('getcategorydeatails/{Currentpage}/{pramater}/{id}/{sort}','CategroryController@GetCategoryProduct')->middleware('apilang');
Route::get('hotads','HotAdsController@GetHotAds')->middleware('apilang');
Route::get('groupshow/{parmater}','GroupShowController@getGroupShow')->middleware('apilang');
Route::get('getgrouppaginate/{parmater}/{GroupShowID}/{Currentpage}','GroupShowController@GroupShowPaginet')->middleware('apilang');
Route::get('groupshowtest/{parmater}','GroupShowController@getGroupShowTest')->middleware('apilang');
Route::get('getwishlist/{id}','WishListController@getWishlistbyUserID')->middleware('apilang');
Route::get('getwishlistfortokenid/{tokenid}','WishListController@getWishlistbyTokenID')->middleware('apilang');
Route::get('hotoffer','HotOfferController@getHotoffer')->middleware('apilang');
Route::Post('serachproduct','HotOfferController@GlobalSearch');
Route::get('getrelatedproduct/{id}','HotOfferController@getOtherOffers');
Route::get('cart/{id}','CartController@getCart')->middleware('apilang');
Route::get('cartunregtsiter/{tokenid}','CartController@getCartForUnRegister')->middleware('apilang');
Route::get('complaintype','ComplainController@getComplainType')->middleware('apilang');
Route::get('Getcomplain','ComplainController@getComplain')->middleware('apilang');
Route::get('Getsupplierscomplain','ComplainController@getComplainForSuppliers')->middleware('apilang');
Route::get('getcompalinforonsupplier/{id}','ComplainController@GetsupplierComplain')->middleware('apilang');
Route::get('about','AboutController@index')->middleware('apilang');
Route::get('productDetails/{id}','ProductController@getProductDetails')->middleware('apilang');
Route::get('userlocation/{id}','LoactionController@getLocationForUser');
Route::get('productBarcode/{barcode}','ProductController@getProductByBarcode');
Route::get('getPoductValue/{id}','ProductPropertyController@getProductProperty')->middleware('apilang');;
Route::delete('deletWishlist/{id}','WishListController@destroy');
Route::post('addlocation','LoactionController@setLocation');
Route::post('register/{lang}','UserController@create');
Route::post('contact','UserController@ContactUs');
Route::put('changenotify/{id}','UserController@NotificationStutes');
Route::post('login/{lang}','UserController@Login');
Route::post('forgetpassword/{lang}','UserController@ForgetPassword');
Route::post('inserttowishlist','WishListController@insetNewItemTOwishlist');
Route::post('inserttowishlistoffline','WishListController@insetNewItemTOwishlistOffline');
Route::post('addcart','CartController@addtocart');
Route::post('addcartoffline','CartController@addToCartOffline');
Route::post('deleteproductfromcart','CartController@deleteProductFromCart');
Route::post('insertcomplain','ComplainController@create');
Route::put('updateComplain/{id}','ComplainController@UpdateCompalin');
Route::delete('deleteComplain/{id}','ComplainController@destroy');
Route::post('productcolor','ProductController@getProductColorImage');
Route::put('updateuser/{id}','UserController@update');
Route::post('getFavorit','ProductController@getFovoritList');
Route::put('modifaycart/{id}','CartController@cartQTY');
Route::put('setDefultLocation/{id}','LoactionController@setDefaultLoctionForUser');
Route::delete('deletecart/{id}','CartController@deleteCart');
Route::delete('deletelocation/{id}','LoactionController@destoryLocation');
Route::post('insertorder','OrderController@createNewOrder');
Route::post('orderhiostry/{id}','OrderController@OrderHistory');
Route::post('deletefav','WishListController@RemoveFavourit');
Route::post('rate','RateController@NewRate');

