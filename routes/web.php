<?php

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

Route::get('/', function () {
    return view('welcome');
});
/* get section for Admin */
Route::get('suppliers', 'AdminPanel\SuppliersController@getSuppliers');
Route::get('getsupplierProduct/{id}', 'AdminPanel\SuppliersController@getNumberofProduct');
Route::get('suppliersStore/{id}', 'AdminPanel\SuppliersController@StoreForEveryProduct');
Route::get('supplersorders/{orderstate}/{UserID}', 'AdminPanel\SuppliersController@OrdersForSupplers');
Route::get('abstarctOrders/{UserID}', 'AdminPanel\SuppliersController@AbstarctOrderState');
Route::get('sellerPosition/', 'AdminPanel\SuppliersController@SellerPosition');
Route::get('orderlate/{UserID}', 'AdminPanel\SuppliersController@LateOrder');
Route::post('adminlateorder', 'AdminPanel\SuppliersController@AdminLateOrder');
Route::get('getadmincruals', 'AdminPanel\SuppliersController@getadministrativecirculars');
Route::post('setadmincruals', 'AdminPanel\SuppliersController@addadministrativecirculars');
Route::put('putadmincruals/{id}', 'AdminPanel\SuppliersController@putaddadministrativecirculars');
Route::delete('deleteadmincruals/{id}', 'AdminPanel\SuppliersController@deleteaddadministrativecirculars');
Route::get('getadmincruals', 'AdminPanel\SuppliersController@getaddadministrativecirculars');
Route::get('getstore/{id}', 'AdminPanel\SuppliersController@getStores')->middleware('apilang');
Route::get('getcity', 'AdminPanel\SuppliersController@getCity')->middleware('apilang');
Route::get('supplierSupport/{id}', 'AdminPanel\CityAdminController@GetCitySupplier')->middleware('apilang');
Route::get('closerder/{id}/{orderState}', 'AdminPanel\SuppliersController@CloseOrder');
Route::post('CancelOrder/{id}', 'AdminPanel\SuppliersController@SupplierCancelOrder');
Route::put('refusedorder/{id}', 'AdminPanel\SuppliersController@RefusedOrder');
Route::get('getadmin', 'AdminPanel\SuppliersController@GetAdminForPanel');
Route::get('getcustomers', 'AdminPanel\SuppliersController@getUsers');
Route::get('clintorder/{id}/{orderstate}', 'AdminPanel\SuppliersController@getClintOrder');
Route::get('getCategroy', 'AdminPanel\CategoryController@index')->middleware('apilang');
Route::get('getProductManagment/{id}', 'AdminPanel\AdminProductController@index')->middleware('apilang');
Route::get('myproduct/{id}', 'AdminPanel\AdminProductController@MyProduct')->middleware('apilang');
Route::get('productunderupdate/{id}', 'AdminPanel\AdminProductController@ProductUnderUpdate')->middleware('apilang');
Route::get('alert/{id}', 'AdminPanel\AdminProductController@alertproduct');
Route::get('productaddadd/{id}', 'AdminPanel\AdminProductController@ProductAddAdd')->middleware('apilang');
Route::get('productimage/{id}', 'AdminPanel\AdminProductController@getProductImage');
Route::get('getfroupshow', 'AdminPanel\GroupShowAdminController@getGroupshow')->middleware('apilang');
Route::get('getsetting', 'AdminPanel\GroupShowAdminController@getsetting');
Route::get('product', 'AdminPanel\GroupShowAdminController@product')->middleware('apilang');
Route::get('productGroupShow/{id}', 'AdminPanel\GroupShowAdminController@ProductGroupShow')->middleware('apilang');
Route::get('productHot/{id}', 'AdminPanel\HotOfferAdminController@ProductHotoffer')->middleware('apilang');
Route::get('gethotoffer', 'AdminPanel\HotOfferAdminController@getHotoffer')->middleware('apilang');
Route::get('productproperty', 'AdminPanel\ProductPropertyAdminController@getProductProperty')->middleware('apilang');
Route::get('productvalue/{id}', 'AdminPanel\ProductPropertyAdminController@getPropertyValue')->middleware('apilang');
Route::get('getPropertyforCategory/{id}', 'AdminPanel\ProductPropertyAdminController@getProrpertyForCategory')->middleware('apilang');
Route::get('getAbout', 'AdminPanel\AboutAdminController@getAbout')->middleware('apilang');
Route::get('hotadds', 'AdminPanel\HotAdsAdminController@index');
Route::get('getchangemode', 'AdminPanel\HotAdsAdminController@getChngeMode');
Route::get('quickupdateimage', 'GroupShowController@UpdateImages');
Route::get('getCity', 'AdminPanel\CityAdminController@getAllCity');
Route::get('getcolor', 'AdminPanel\ColorAdminController@GetColor');


Route::get('seller/{id}', 'AdminPanel\SellerStoreController@getSellerStre')->middleware('apilang');
Route::get('sellerproduct/{id}', 'AdminPanel\SellerStoreController@getproductforseller')->middleware('apilang');
Route::get('lastAdminRecorede', 'AdminPanel\AdminDashBoardController@LastRecored');
Route::get('readcompalin/{id}', 'ComplainController@ReadCompalin');
Route::get('moveup/{id}', 'ComplainController@Flag');
Route::get('removemoveup/{id}', 'ComplainController@removePain');
Route::get('usercompalin/{id}', 'ComplainController@GetUserCompalin');
Route::get('getpendingProduct', 'AdminPanel\AdminProductController@getPendingProduct')->middleware('apilang');
Route::get('finalproductqty/{ProductID}/{StoreID}/{Date}/{userid}', 'AdminPanel\AdminProductController@getFinalProductQTY');


/* post section for Admin */
Route::post('create', 'AdminPanel\SuppliersController@createNewSuppliers');
Route::post('loginadmin', 'AdminPanel\SuppliersController@SgininAdmin');
Route::post('insertnewImage', 'AdminPanel\AdminProductController@InsertImage');
Route::post('getFinalProductQTY', 'AdminPanel\AdminProductController@getFinalProductlistQTY');
Route::post('setgroup', 'AdminPanel\GroupShowAdminController@setGrouShow')->middleware('apilang');
Route::post('sethotoffer', 'AdminPanel\HotOfferAdminController@setHotoffer');
Route::post('assgin', 'AdminPanel\GroupShowAdminController@assginProducttoGroup')->middleware('apilang');
Route::post('addhotoffer', 'AdminPanel\HotOfferAdminController@ProducttoHotOffer')->middleware('apilang');
Route::post('createproperty', 'ProductPropertyController@createNewPropductProperty');
Route::post('createpropertyvalue', 'PropertyValueController@assginProducttoGroup');
Route::post('storeproperty', 'AdminPanel\ProductPropertyAdminController@storeProductProperty')->middleware('apilang');
Route::post('storevalue', 'AdminPanel\ProductPropertyAdminController@addpropertyvaluetoproduct')->middleware('apilang');
Route::post('createsize', 'AdminPanel\SizeController@store')->middleware('apilang');
Route::post('inserthotadds', 'AdminPanel\HotAdsAdminController@store');
Route::post('setSellerStore', 'AdminPanel\SellerStoreController@setNewSellerStore')->middleware('apilang');
Route::post('asginsllerproduct', 'AdminPanel\SellerStoreController@assginproduct')->middleware('apilang');
Route::post('creatcity', 'AdminPanel\CityAdminController@CreateCity');
Route::post('addtomylist', 'AdminPanel\CityAdminController@SetMyList');
Route::post('creatcolor', 'AdminPanel\ColorAdminController@CreateColor');

/*repost*/


Route::post('sellsreports', 'AdminPanel\Ar5ssReportController@SallesReport')->middleware('apilang');
Route::post('abstractReport', 'AdminPanel\Ar5ssReportController@SelesReportAbstract')->middleware('apilang');
Route::post('userReport', 'AdminPanel\Ar5ssReportController@UsersReport')->middleware('apilang');
Route::post('filterReport', 'AdminPanel\Ar5ssReportController@FilterSalesOnDashbord');
Route::post('fillterSalesReport', 'AdminPanel\Ar5ssReportController@FiltterSalesByBrand');
Route::post('FilterCat', 'AdminPanel\Ar5ssReportController@FiltterSalesByCategory');
Route::post('FiltterProduct', 'AdminPanel\Ar5ssReportController@FiltterSalesByProduct');
Route::post('filterslasebyday', 'AdminPanel\Ar5ssReportController@FilterSalesbyDayOnDashbord');
Route::post('budget', 'AdminPanel\Ar5ssReportController@Budgeting');
Route::post('clims', 'AdminPanel\Ar5ssReportController@Clims');
Route::post('budgtingsuppleris', 'AdminPanel\Ar5ssReportController@BudgtingSuppleris');
Route::post('productsuppliersell', 'AdminPanel\Ar5ssReportController@GetBudGetingSupplier');
Route::post('abstractOrderSupplier', 'AdminPanel\Ar5ssReportController@abstractSupplier');
Route::post('abstractOrderSupplier2', 'AdminPanel\Ar5ssReportController@abstractSupplier2');
Route::post('abstractOrderSupplier3', 'AdminPanel\Ar5ssReportController@abstractSupplier3');
Route::post('excutedOrders', 'AdminPanel\Ar5ssReportController@ExcutedOrders');
Route::post('actualaccount', 'AdminPanel\Ar5ssReportController@GetActualAccount');
Route::put('changefincorder/{id}/{state}', 'AdminPanel\Ar5ssReportController@ChangeFincialOrderState');


Route::get('getsalesdashboard', 'AdminPanel\Ar5ssReportController@SalesOnDashbord')->middleware('apilang');
Route::get('salesbybrand', 'AdminPanel\Ar5ssReportController@SalesByBrand');
Route::get('salesbyday', 'AdminPanel\Ar5ssReportController@SalesByDayOnDashbord');
Route::get('salesCat', 'AdminPanel\Ar5ssReportController@SalesByCategory');
Route::get('salesProduct', 'AdminPanel\Ar5ssReportController@SalesByProduct');
Route::post('cashorder', 'AdminPanel\Ar5ssReportController@OrderCash');
Route::post('epaymentorder', 'AdminPanel\Ar5ssReportController@EpaymentOrder');
Route::get('ar5sspresnt', 'AdminPanel\Ar5ssReportController@getAr5sspresnt');
Route::get('orderforsuppliers/{id}', 'AdminPanel\Ar5ssReportController@getorderforSupplers');
Route::get('supplerorderepayment/{id}', 'AdminPanel\Ar5ssReportController@getorderforSupplersEpayment');
Route::Post('fillterorderforsuppliers/{id}', 'AdminPanel\Ar5ssReportController@FilltergetorderforSupplers');
Route::Post('filltergetorderforsupplersepayment/{id}', 'AdminPanel\Ar5ssReportController@FilltergetorderforSupplersEpayment');


/* Update section for Admin*/
Route::put('updateSupliers/{id}', 'AdminPanel\SuppliersController@updateSuppliers');
Route::put('supllirsstate/{id}', 'AdminPanel\SuppliersController@stopSuppliers');
Route::put('setUserToAdmin/{id}/{useState}', 'AdminPanel\SuppliersController@setAdmin');
Route::put('updategroup/{id}', 'AdminPanel\GroupShowAdminController@update')->middleware('apilang');
Route::put('updatehot/{id}', 'AdminPanel\HotOfferAdminController@update')->middleware('apilang');
Route::put('removeproductfromgroup/{id}', 'AdminPanel\GroupShowAdminController@RemoveProductGroup')->middleware('apilang');
Route::put('groupsetting/{id}', 'AdminPanel\GroupShowAdminController@setsetting');
Route::put('removeproductfromhot/{id}', 'AdminPanel\HotOfferAdminController@RemoveHotOffer')->middleware('apilang');
Route::put('putproperty/{id}', 'AdminPanel\ProductPropertyAdminController@update')->middleware('apilang');
Route::put('putvalue/{id}', 'AdminPanel\ProductPropertyAdminController@updatepropertyvalue')->middleware('apilang');
Route::put('updatesize/{id}', 'AdminPanel\SizeController@update')->middleware('apilang');
Route::put('updateAbout/{id}', 'AdminPanel\AboutAdminController@Update')->middleware('apilang');
Route::put('updatehotadds/{id}', 'AdminPanel\HotAdsAdminController@update');
Route::put('changemode', 'AdminPanel\HotAdsAdminController@ChangeMode');
Route::put('assgincorp/{id}', 'AdminPanel\HotAdsAdminController@AssginProductOrCategoryForHotAdds');
Route::put('updateSeller/{id}/{StoreID}', 'AdminPanel\SellerStoreController@upDateSellerStore')->middleware('apilang');
Route::put('aproveproduct/{id}', 'AdminPanel\AdminProductController@AprooveProduct');
Route::put('updateproductqty/{id}', 'AdminPanel\AdminProductController@UpdateProductQty');
Route::put('updateproductforanthermonthe/{id}', 'AdminPanel\AdminProductController@UpdatedProductForMonthe');
Route::put('updatear5sspresntage', 'AdminPanel\Ar5ssReportController@updatePresentage');
Route::put('updatecity/{id}', 'AdminPanel\CityAdminController@UpdateCity');
Route::put('updatecolor/{id}', 'AdminPanel\ColorAdminController@UpdateColor');
Route::put('updatevat/{id}', 'AdminPanel\VatAdminController@updateVat');
/*delete section*/
Route::delete('deleteImage/{id}/{procolorid}', 'AdminPanel\AdminProductController@DeleteImage');
Route::delete('deletegroupshow/{id}', 'AdminPanel\GroupShowAdminController@delete');
Route::delete('deleteghotoffer/{id}', 'AdminPanel\HotOfferAdminController@delete');
Route::delete('deleteproperty/{id}', 'AdminPanel\ProductPropertyAdminController@delete');
Route::delete('deletevalue/{id}', 'AdminPanel\ProductPropertyAdminController@deletepropertyvalue');
Route::delete('deletehotadds/{id}', 'AdminPanel\HotAdsAdminController@delete');
Route::delete('deletesellerstore/{id}', 'AdminPanel\SellerStoreController@deleteSellerStore');
Route::delete('deleteproductfromstore/{id}/{SellerPrductID}', 'AdminPanel\SellerStoreController@DeleteProductFromStore');
Route::delete('removeEmployee/{id}', 'AdminPanel\SuppliersController@DeleteEmployee');
Route::put('deletesize/{id}', 'AdminPanel\SizeController@destroy')->middleware('apilang');
Route::delete('deleteproduct/{id}', 'AdminPanel\AdminProductController@RemoveProduct');
Route::delete('deleteproductAdmin/{id}', 'AdminPanel\AdminProductController@DeleteProduct');
Route::delete('removetomylist/{id}', 'AdminPanel\CityAdminController@removeFrommylist');

/** resource Routes**/
Route::resource('getBrand', 'AdminPanel\BrandController');
Route::resource('category', 'AdminPanel\CategoryController');
Route::put('updatecategory/{id}', 'AdminPanel\CategoryController@update')->middleware('apilang');;
Route::put('hidecategory/{id}', 'AdminPanel\CategoryController@HideCategory');
Route::get('cat', 'AdminPanel\CategoryController@index')->middleware('apilang');
Route::resource('product', 'AdminPanel\AdminProductController');
Route::get('getproduct', 'AdminPanel\AdminProductController@index')->middleware('apilang');
Route::get('Product', 'AdminPanel\AdminProductController@getProduct')->middleware('apilang');
Route::get('allproduct', 'AdminPanel\AdminProductController@AllProduct')->middleware('apilang');
Route::get('productforusers/{id}', 'AdminPanel\AdminProductController@getProductForSeller')->middleware('apilang');
Route::resource('size', 'AdminPanel\SizeController');
Route::get('size', 'AdminPanel\SizeController@index');
Route::get('sizerealted/{id}', 'AdminPanel\SizeController@show');
Route::resource('color', 'AdminPanel\ColorController');
Route::get('/', function () {
    return view('welcome');
});

