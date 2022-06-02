<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\TestimoniController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ResellerController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\CommisionController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\RajaOngkirController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CouponController;

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
    return view('home');
});


// Admin
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');

    // Homepage
    //    Route::group(['prefix' => 'homepage', 'middleware' => 'check.permission.admin'], function () {
    //        // Main Content
    //        Route::group(['prefix' => 'MainContent'], function () {
    //            Route::get('edit', [HomepageController::class, 'editMainContent'])->name('admin.mainContent.edit');
    //            Route::post('update', [HomepageController::class, 'updateMainContent'])->name('admin.mainContent.update');
    //        });
    //        // Featured
    //        Route::group(['prefix' => 'featured'], function () {
    //            Route::get('/', [HomepageController::class, 'featured'])->name('admin.featured');
    //            Route::get('create', [HomepageController::class, 'createFeatured'])->name('admin.featured.create');
    //            Route::get('/edit/banner', [HomepageController::class, 'editFeaturedBanner'])->name('admin.featured.banner.edit');
    //            Route::post('/update/banner', [HomepageController::class, 'updateFeaturedBanner'])->name('admin.featured.banner.update');
    //            Route::get('edit/{id}', [HomepageController::class, 'editFeatured'])->name('admin.featured.edit');
    //            Route::post('save', [HomepageController::class, 'saveFeatured'])->name('admin.featured.save');
    //            Route::post('update/{id}', [HomepageController::class, 'updateFeatured'])->name('admin.featured.update');
    //        });
    //
    //        // Keuntungan
    //        Route::group(['prefix' => 'keuntungan'], function () {
    //            Route::get('/', [HomepageController::class, 'keuntungan'])->name('admin.keuntungan');
    //            Route::get('create', [HomepageController::class, 'createKeuntungan'])->name('admin.keuntungan.create');
    //            Route::get('edit/{id}', [HomepageController::class, 'editKeuntungan'])->name('admin.keuntungan.edit');
    //            Route::post('save', [HomepageController::class, 'saveKeuntungan'])->name('admin.keuntungan.save');
    //            Route::post('update/{id}', [HomepageController::class, 'updateKeuntungan'])->name('admin.keuntungan.update');
    //        });
    //    });

    // Product
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.product');
        Route::get('/asadmin', [ProductController::class, 'indexAdmin'])->name('admin.product.asadmin');
        Route::get('create', [ProductController::class, 'create'])->name('admin.product.create');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::post('image/upload/{id}', [ProductController::class, 'uploadImage'])->name('admin.product.upload.image');
        Route::post('video/upload/{id}', [ProductController::class, 'uploadVideo'])->name('admin.product.upload.video');
        Route::get('image/delete/{id}/{product}', [ProductController::class, 'deleteImage'])->name('admin.product.delete.image');
        Route::post('save', [ProductController::class, 'save'])->name('admin.product.save');
        Route::post('update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::get('edit/category/{id}', [CategoryController::class, 'categorySelect'])->name('admin.category.select.ajax2');
        Route::get('category/{id}', [CategoryController::class, 'categorySelect'])->name('admin.category.select.ajax');
    });

    // Varian Product
    Route::group(['prefix' => 'varian'], function () {
        Route::post('save/{id}', [ProductController::class, 'varianSave'])->name('admin.varianProduct.save');
        Route::get('edit/{product_id}/{id}', [ProductController::class, 'varianEdit'])->name('admin.varianProduct.edit');
        Route::post('update/{product_id}/{id}', [ProductController::class, 'varianUpdate'])->name('admin.varianProduct.update');
        Route::get('delete/{id}', [ProductController::class, 'varianDelete'])->name('admin.varianProduct.delete');
    });

    // Category
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('admin.category');
        Route::get('create', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit')->middleware('check.permission.admin');
        Route::post('save', [CategoryController::class, 'save'])->name('admin.category.save');
        Route::post('update/{id}', [CategoryController::class, 'update'])->name('admin.category.update')->middleware('check.permission.admin');
    });

    // Sub Category
    Route::group(['prefix' => 'subcategory'], function () {
        Route::get('/', [CategoryController::class, 'subcategory'])->name('admin.subcategory');
        Route::get('create', [CategoryController::class, 'createSubCategory'])->name('admin.subcategory.create');
        Route::get('edit/{id}', [CategoryController::class, 'editSubCategory'])->name('admin.subcategory.edit')->middleware('check.permission.admin');;
        Route::post('save', [CategoryController::class, 'saveSubCategory'])->name('admin.subcategory.save');
        Route::post('update/{id}', [CategoryController::class, 'updateSubCategory'])->name('admin.subcategory.update')->middleware('check.permission.admin');;
    });

    // Brand
    Route::group(['prefix' => 'brand'], function () {
        Route::get('/', [BrandController::class, 'index'])->name('admin.brand');
        Route::get('create', [BrandController::class, 'create'])->name('admin.brand.create');
        Route::get('edit/{id}', [BrandController::class, 'edit'])->name('admin.brand.edit');
        Route::post('save', [BrandController::class, 'save'])->name('admin.brand.save');
        Route::post('update/{id}', [BrandController::class, 'update'])->name('admin.brand.update');
    });

    // Testimoni
    Route::group(['prefix' => 'testimoni', 'middleware' => 'check.permission.admin'], function () {
        Route::get('/', [TestimoniController::class, 'index'])->name('admin.testimoni');
        Route::get('block/{id}', [TestimoniController::class, 'block'])->name('admin.testimoni.block');
    });

    // Comment
    Route::group(['prefix' => 'comment', 'middleware' => 'check.permission.admin'], function () {
        Route::get('/', [CommentController::class, 'index'])->name('admin.comment');
        Route::get('block/{id}', [CommentController::class, 'block'])->name('admin.comment.block');
    });

    // Notifikasi
    Route::group(['prefix' => 'notification', 'middleware' => 'check.permission.admin'], function () {
        Route::get('/', [NotificationController::class, 'index'])->name('admin.notification');
        Route::get('create', [NotificationController::class, 'create'])->name('admin.notification.create');
        Route::get('edit/{id}', [NotificationController::class, 'edit'])->name('admin.notification.edit');
        Route::post('save', [NotificationController::class, 'save'])->name('admin.notification.save');
        Route::post('update/{id}', [NotificationController::class, 'update'])->name('admin.notification.update');
    });

    // Kupon
    Route::group(['prefix' => 'coupon', 'middleware' => 'check.permission.admin'], function () {
        Route::get('/', [CouponController::class, 'index'])->name('admin.coupon');
        Route::get('create', [CouponController::class, 'create'])->name('admin.coupon.create');
        Route::get('edit/{id}', [CouponController::class, 'edit'])->name('admin.coupon.edit');
        Route::post('save', [CouponController::class, 'save'])->name('admin.coupon.save');
        Route::post('update/{id}', [CouponController::class, 'update'])->name('admin.coupon.update');
    });

    // Supplier
    Route::group(['prefix' => 'supplier', 'middleware' => 'check.permission.admin'], function () {
        Route::get('/', [SupplierController::class, 'index'])->name('admin.supplier');
        //        Route::get('create', [SupplierController::class, 'create'])->name('admin.supplier.create');
        Route::get('edit/{id}', [SupplierController::class, 'edit'])->name('admin.supplier.edit');
        //        Route::post('save', [SupplierController::class, 'save'])->name('admin.supplier.save');
        Route::post('update/{id}', [SupplierController::class, 'update'])->name('admin.supplier.update');
        Route::get('reports', [SupplierController::class, 'exports'])->name('admin.supplier.reports');
    });

    // Reseller
    Route::group(['prefix' => 'reseller'], function () {
        Route::get('/', [ResellerController::class, 'index'])->name('admin.reseller');
        //        Route::get('create', [SupplierController::class, 'create'])->name('admin.reseller.create');
        Route::get('edit/{id}', [ResellerController::class, 'edit'])->name('admin.reseller.edit')->middleware('check.permission.admin');
        //        Route::post('save', [SupplierController::class, 'save'])->name('admin.reseller.save');
        Route::post('update/{id}', [ResellerController::class, 'update'])->name('admin.reseller.update');
        Route::get('reports', [ResellerController::class, 'exports'])->name('admin.reseller.reports');
    });

    // Customer
    Route::group(['prefix' => 'customer', 'middleware' => 'check.permission.admin'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('admin.customer');
        //        Route::get('create', [SupplierController::class, 'create'])->name('admin.reseller.create');
        Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('admin.customer.edit')->middleware('check.permission.admin');;
        //        Route::post('save', [SupplierController::class, 'save'])->name('admin.reseller.save');
        Route::post('update/{id}', [CustomerController::class, 'update'])->name('admin.customer.update');
    });

    // Profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::post('update/{id}', [ProfileController::class, 'update'])->name('admin.profile.update');
    });

    // Alamat
    Route::group(['prefix' => 'address'], function () {
        Route::get('/', [AddressController::class, 'index'])->name('admin.address');
        Route::get('/asadmin', [AddressController::class, 'indexAdmin'])->name('admin.address.asadmin');
        Route::get('cities/{id}', [RajaOngkirController::class, 'getCities']);
        Route::get('districts/{id}', [RajaOngkirController::class, 'getDistricts']);
        Route::post('save', [AddressController::class, 'save'])->name('admin.address.save');
        Route::get('update/status/{id}', [AddressController::class, 'updateStatus'])->name('admin.address.update.status');
        Route::get('delete/{id}', [AddressController::class, 'delete'])->name('admin.address.delete');
    });

    // Transaksi
    Route::group(['prefix' => 'transaction'], function () {
        Route::get('/reseller', [TransactionController::class, 'resellerTransaction'])->name('admin.resellerTransaction');
        Route::get('/customer', [TransactionController::class, 'customerTransaction'])->name('admin.customerTransaction');
        Route::get('/confirmation/{id}/{status}', [TransactionController::class, 'confirmation'])->name('admin.confirmation');
        Route::post('/confirmation/process', [TransactionController::class, 'processConfirmation'])->name('process.confirmation');
        Route::post('/resi/update', [TransactionController::class, 'updateResi'])->name('resi.update');
    });

    // Pembayaran
    Route::group(['prefix' => 'payment'], function () {
        Route::get('/reseller', [PaymentController::class, 'resellerPayment'])->name('admin.resellerPayment');
        Route::get('/customer', [PaymentController::class, 'customerPayment'])->name('admin.customerPayment');
        Route::get('/confirm/{id}', [PaymentController::class, 'confirm'])->name('admin.confirm.payment');
        Route::post('/update/{id}', [PaymentController::class, 'update'])->name('admin.update.payment');
    });

    // Komisi Reseller
    Route::group(['prefix' => 'commision'], function () {
        Route::get('/reseller', [CommisionController::class, 'index'])->name('admin.commision');
    });

    // Rekening
    Route::group(['prefix' => 'bank/account'], function () {
        Route::get('/', [BankAccountController::class, 'index'])->name('admin.bankAccount');
        Route::get('/asadmin', [BankAccountController::class, 'indexAdmin'])->name('admin.bankAccount.asadmin');
        Route::get('create', [BankAccountController::class, 'create'])->name('admin.bankAccount.create');
        Route::post('save', [BankAccountController::class, 'save'])->name('admin.bankAccount.save');
        Route::get('edit/{id}', [BankAccountController::class, 'edit'])->name('admin.bankAccount.edit');
        Route::post('update/{id}', [BankAccountController::class, 'update'])->name('admin.bankAccount.update');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
