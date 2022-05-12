<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Reseller\ResellerAuthController;
use App\Http\Controllers\API\RajaOngkirController;
use App\Http\Controllers\API\Reseller\HomepageController;
use App\Http\Controllers\API\Reseller\ProductController;
use App\Http\Controllers\API\Reseller\ProfileController;
use App\Http\Controllers\API\Reseller\CheckOutController;
use App\Http\Controllers\API\MultiPlatform\MultiFlatformController;
use App\Http\Controllers\API\Reseller\SupplierController;
use App\Http\Controllers\API\Reseller\BrandController;
use App\Http\Controllers\API\Reseller\CategoryController;
use App\Http\Controllers\API\Customer\CustomerAuthController;
use App\Http\Controllers\API\Reseller\BankAccountController;
use App\Http\Controllers\API\Reseller\TransactionController;
use App\Http\Controllers\API\Customer\DetailReseller;
use App\Http\Controllers\API\Customer\CategoryReseller;
use App\Http\Controllers\API\Customer\AddressCustomer;
use App\Http\Controllers\API\Customer\ProductCustomer;
use App\Http\Controllers\API\Customer\CustomerCheckoutController;
use App\Http\Controllers\API\Customer\CustomerTransactionController;

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
//
// Route::get('count_order', [TransactionController::class, 'count_order']);
Route::post('provinces', [RajaOngkirController::class, 'insertProvinces']);
Route::post('city', [RajaOngkirController::class, 'insertCities']);
//Route::post('district', [RajaOngkirController::class, 'insertDistrict']);


Route::group(['prefix' => 'reseller', 'namespace' => 'Reseller'], function () {
    Route::post('register', [ResellerAuthController::class, 'register']);
    Route::post('login', [ResellerAuthController::class, 'login']);
    Route::post('forgot', [ResellerAuthController::class, 'forgot']);
    Route::post('check/validasi/send/otp', [ResellerAuthController::class, 'checkValidation']);
    Route::post('send/otp', [ResellerAuthController::class, 'sendOtp']);
    Route::post('tes', function (Request $request) {
        return $request->file('image')->store('storage');
    });
    Route::get('gallery', [ResellerAuthController::class, 'product']);
    Route::get('gallery/detail', [ResellerAuthController::class, 'detail']);


    Route::group(['middleware' => 'auth:reseller-api'], function () {

        Route::post('logout', [ResellerAuthController::class, 'logout']);
        Route::post('refresh', [ResellerAuthController::class, 'refresh']);
        Route::get('account', [ResellerAuthController::class, 'account']);

        Route::group(['prefix' => 'homepage'], function () {
            Route::get('category', [HomepageController::class, 'allCategoryProduct']);
            Route::get('brand', [HomepageController::class, 'allBrand']);
            Route::get('product', [HomepageController::class, 'product']);
            Route::get('product/new', [HomepageController::class, 'newProduct']);
            Route::get('product/popular', [HomepageController::class, 'productPopular']);
            Route::post('product/share', [HomepageController::class, 'shareProduct']);
        });

        Route::group(['prefix' => 'product'], function () {
            Route::get('detail', [ProductController::class, 'detail']);
            Route::get('beli', [ProductController::class, 'beli']);
            Route::get('cart', [ProductController::class, 'allCart']);
            Route::get('recomendation', [ProductController::class, 'recomendation']);
            Route::get('bycategory', [ProductController::class, 'filterProductByCategory']);
            Route::get('bysubcategory', [ProductController::class, 'filterProductBySubCategory']);
            Route::post('cart', [ProductController::class, 'cart']);
            Route::post('increment/order/total', [ProductController::class, 'incrementOrder']);
            Route::post('decrement/order/total', [ProductController::class, 'decrementOrder']);
            Route::post('delete/multiple', [ProductController::class, 'multipleDeleteCart']);
            Route::post('', [ProductController::class, 'addToMyShop']);
        });

        Route::group(['prefix' => 'checkout'], function () {
            Route::get('shipping/type', [CheckOutController::class, 'shippingType']);
            Route::post('cart', [CheckOutController::class, 'checkOutCart']);
            Route::post('transaction', [CheckOutController::class, 'transaction']);
            Route::put('addressupdate', [CheckOutController::class, 'addressupdate']);
            Route::post('reguler/courier', [CheckOutController::class, 'ongkirReguler']);
            Route::post('reguler/courier/cekkota', [CheckOutController::class, 'cekKota']);
            Route::post('reguler/courier/cekprov', [CheckOutController::class, 'cekProv']);
            Route::post('reguler/courier/cekkec', [CheckOutController::class, 'cekKec']);
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('', [ProfileController::class, 'index']);
            Route::post('password/change', [ProfileController::class, 'changePassword']);
            Route::get('referal', [ProfileController::class, 'referal']);
            Route::get('point', [ProfileController::class, 'point']);
            Route::post('swap/coupon', [ProfileController::class, 'swapCoupon']);
            Route::get('address', [ProfileController::class, 'address']);
            Route::get('address/detail', [ProfileController::class, 'detail']);
            Route::post('address', [ProfileController::class, 'insertAddress']);
            Route::post('address/detail', [ProfileController::class, 'editAddress']);
            Route::post('address/status/change', [ProfileController::class, 'changeStatus']);
        });

        Route::group(['prefix' => 'brand'], function () {
            Route::get('filter/byabjad', [BrandController::class, 'filterBrandByAbjad']);
            Route::get('search', [BrandController::class, 'search']);
            Route::get('filter/popular', [BrandController::class, 'populer']);
        });

        Route::group(['prefix' => 'bank'], function () {
            Route::get('account', [BankAccountController::class, 'list']);
            Route::post('account/add', [BankAccountController::class, 'add']);
            Route::post('account/edit', [BankAccountController::class, 'edit']);
        });

        Route::group(['prefix' => 'supplier'], function () {
            Route::get('brand/product/all', [SupplierController::class, 'allProduct']);
            Route::get('brand/product/new', [SupplierController::class, 'newProduct']);
            Route::get('brand/product/populer', [SupplierController::class, 'populerProduct']);
            Route::get('brand/category', [SupplierController::class, 'category']);

            Route::group(['prefix' => 'product'], function () {
                Route::get('subcategory', [SupplierController::class, 'productBySubCategory']);
                Route::get('search/subcategory', [SupplierController::class, 'searchProductBySubCategory']);
            });
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('count_order', [TransactionController::class, 'count_order']);
            Route::get('unpaid', [TransactionController::class, 'unpaid']);
            Route::get('unpaid/single', [TransactionController::class, 'unpaid_single']);
            Route::post('pay', [TransactionController::class, 'payment']);
            Route::get('process', [TransactionController::class, 'process']);
            Route::post('cancel/pesanan', [TransactionController::class, 'cancelTransaction']);
            Route::get('sent', [TransactionController::class, 'sent']);
            Route::get('beli', [TransactionController::class, 'beli']);
            Route::post('accepted/pesanan', [TransactionController::class, 'pesananDiterima']);
            Route::post('track', [TransactionController::class, 'trackTransaction']);
            Route::get('done', [TransactionController::class, 'done']);
            Route::post('ulas/pesanan', [TransactionController::class, 'tambahUlasan']);
            Route::get('cancel', [TransactionController::class, 'cancel']);
            Route::get('returned', [TransactionController::class, 'returned']);
            Route::get('image', [TransactionController::class, 'image']);
        });
    });
});

Route::group(['prefix' => 'global', 'namespace' => 'Global'], function () {
    Route::get('coupon', [MultiFlatformController::class, 'coupon']);
    Route::get('reseller/coupon', [MultiFlatformController::class, 'resellerCoupon']);
    Route::get('category', [CategoryController::class, 'category']);
    Route::get('search/category', [CategoryController::class, 'searchCategory']);
    Route::get('filter/category', [CategoryController::class, 'filterCategory']);
    Route::get('subcategory', [CategoryController::class, 'subcategory']);
    Route::post('coupon', [MultiFlatformController::class, 'checkCoupon']);
    Route::get('search/product', [MultiFlatformController::class, 'searchProduct']);
    Route::get('notification', [MultiFlatformController::class, 'notification']);
    Route::get('supplier/chat', [MultiFlatformController::class, 'chatSupplier']);
    Route::get('invite/friends', [MultiFlatformController::class, 'inviteFriends']);
    Route::get('share/product', [MultiFlatformController::class, 'shareProduct']);
    Route::get('rekening/bersama', [MultiFlatformController::class, 'rekeningBersama']);
    Route::get('image/url', [MultiFlatformController::class, 'imageUrl']);
    Route::get('image/media_code', [MultiFlatformController::class, 'mediaCode']);
});


Route::group(['prefix' => 'customer', 'namespace' => 'Customer'], function () {
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::post('logout', [CustomerAuthController::class, 'logout'])->middleware('auth:customer-api');

    Route::group(['prefix' => 'homepage'], function () {
        Route::get('detail', [DetailReseller::class, 'detail']);
        Route::get('category', [DetailReseller::class, 'category']);
        Route::get('chat/reseller', [DetailReseller::class, 'chatReseller']);
        Route::get('product', [DetailReseller::class, 'product']);
    });

    Route::get('category', [CategoryReseller::class, 'category']);
    Route::get('search/category', [CategoryReseller::class, 'searchCategory']);
    Route::get('filter/category', [CategoryReseller::class, 'filterCategory']);
    // Route::get('subcategory', [CategoryReseller::class, 'subcategory']);
    Route::get('product/by/subcategory', [CategoryReseller::class, 'productBySubCategory']);
    Route::get('search/product/by/subcategory', [CategoryReseller::class, 'searchProductBySubCategory']);

    Route::group(['prefix' => 'account', 'middleware' => 'auth:customer-api'], function () {
        Route::get('', [AddressCustomer::class, 'index']);
        Route::post('password/change', [AddressCustomer::class, 'changePassword']);
        Route::get('address', [AddressCustomer::class, 'address']);
        Route::get('address/detail', [AddressCustomer::class, 'detail']);
        Route::post('address', [AddressCustomer::class, 'insertAddress']);
        Route::post('address/detail', [AddressCustomer::class, 'editAddress']);
        Route::post('address/status/change', [AddressCustomer::class, 'changeStatus']);
    });


    Route::group(['prefix' => 'product'], function () {
        Route::get('detail', [ProductCustomer::class, 'detail']);
        Route::get('beli', [ProductCustomer::class, 'beli'])->middleware('auth:customer-api');
        Route::get('cart', [ProductCustomer::class, 'allCart'])->middleware('auth:customer-api');
        //        Route::get('recomendation', [ProductCustomer::class, 'recomendation']);
        //        Route::get('bycategory', [ProductCustomer::class, 'filterProductByCategory']);
        Route::post('cart', [ProductCustomer::class, 'cart'])->middleware('auth:customer-api');
        Route::post('increment/order/total', [ProductCustomer::class, 'incrementOrder'])->middleware('auth:customer-api');
        Route::post('decrement/order/total', [ProductCustomer::class, 'decrementOrder'])->middleware('auth:customer-api');
        Route::post('delete/multiple', [ProductCustomer::class, 'multipleDeleteCart'])->middleware('auth:customer-api');
        //        Route::post('', [ProductCustomer::class, 'addToMyShop']);
    });

    Route::group(['prefix' => 'checkout', 'middleware' => 'auth:customer-api'], function () {
        Route::get('shipping/type', [CustomerCheckOutController::class, 'shippingType']);
        Route::post('cart', [CustomerCheckOutController::class, 'checkOutCart']);
        Route::post('transaction', [CustomerCheckOutController::class, 'transaction']);
        Route::post('reguler/courier', [CustomerCheckOutController::class, 'ongkirReguler']);
    });

    Route::group(['prefix' => 'transaction', 'middleware' => 'auth:customer-api'], function () {

        Route::get('unpaid', [CustomerTransactionController::class, 'unpaid']);
        Route::post('pay', [CustomerTransactionController::class, 'payment']);
        Route::get('process', [CustomerTransactionController::class, 'process']);
        Route::post('cancel/pesanan', [CustomerTransactionController::class, 'cancelTransaction']);
        Route::get('sent', [CustomerTransactionController::class, 'sent']);
        Route::get('beli', [CustomerTransactionController::class, 'beli']);
        Route::post('accepted/pesanan', [CustomerTransactionController::class, 'pesananDiterima']);
        Route::post('track', [CustomerTransactionController::class, 'trackTransaction']);
        Route::get('done', [CustomerTransactionController::class, 'done']);
        Route::post('ulas/pesanan', [CustomerTransactionController::class, 'tambahUlasan']);
        Route::get('cancel', [CustomerTransactionController::class, 'cancel']);
        Route::get('returned', [CustomerTransactionController::class, 'returned']);
        Route::get('image', [CustomerTransactionController::class, 'image']);
    });
});
// Route::get('pay', [TransactionController::class, 'payment']);
// Route::post('pay', [TransactionController::class, 'payment']);
