<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionType;
use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategorySupplierLog;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductVarian;
use App\Models\SubCategory;
use App\Traits\AdminGeneralTrait;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller
{
    use AdminGeneralTrait;
    public function index(Request $request) {
        $search = $request->get('q');
        $modeAdmin = false;
        $listProduct = Product::with(['category', 'user', 'user:username'])->orderByDesc('id')->whereUserId(Auth::user()->id)->where('title', 'like', "%".$search."%")->paginate(20);
        return view('admin.product.index', [
            'listProduct' => $listProduct,
            'modeAdmin' => $modeAdmin
        ]);
    }
    public function indexAdmin(Request $request) {
        $search = $request->get('q');
        $listProduct = Product::with(['category', 'user'])->orderByDesc('id')->where('title', 'like', "%".$search."%")->paginate(20);
        $modeAdmin = true;
        return view('admin.product.index', [
            'listProduct' => $listProduct,
            'modeAdmin' => $modeAdmin
        ]);
    }

    public function create() {
        $listBrand = Brand::whereStatus(StatusType::PUBLISHED)->get();
        $listCategory = Category::whereCategoryType(CategoryType::PRODUCT)->whereStatus(StatusType::PUBLISHED)->get();
        return view('admin.product.create', compact('listCategory','listBrand'));
    }

    public function save(Request $request) {
        $this->validate($request, [
            'title' => 'required|unique:products',
            'stock' => 'required|numeric|min:0',
            'reseller_price' => 'required|numeric|min:0',
            'customer_price' => 'required|numeric|min:0',
            'catalog_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|lt:catalog_price|min:0',
            'opsi_commision' => 'nullable',
            'point' => 'required|numeric|min:0',
            'point_product' => 'required|numeric|min:1',
            'description' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'commision_rp' => 'required|min:1'
           // 'brand' => 'nullable'
        ]);
        $randomCode = substr(str_shuffle(str_repeat("23456789abcdefghjkmnpqrstuvwxyz", 5)), 2, 7);
        $product = new Product();
        $product->commision_rp = $request->commision_rp;
        $product->title = $request->title;
        //$product->brand_id = $request->brand;
        $product->stock = $request->stock;
        $product->reseller_price = $request->reseller_price;
        $product->customer_price = $request->customer_price;
        $product->catalog_price = $request->catalog_price;
        $product->isCommisionRupiah = true;
        $product->discount = $request->discount;
        $product->point = $request->point;
        $product->point_product = $request->point_product;
        $product->description = $request->description;
        $product->media_code = $randomCode;
        $product->status = StatusType::DRAFT;
        $product->slug = Str::slug($request->title);
        $product->user_id = Auth::id();
        $product->category_id = $request->category;
        $product->sub_category_id = $request->subcategory;
        $product->save();

        $existCategory = CategorySupplierLog::whereCategoryId($request->category)->whereUserId(Auth::id())->first();
        if (empty($existCategory)) {
            $logCategorySupplier = new CategorySupplierLog();
            $logCategorySupplier->user_id = Auth::id();
            $logCategorySupplier->category_id = $request->category;
            $logCategorySupplier->save();
        }
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        $productDetail = Product::where('slug', '=', Str::slug($request->title))->first();
        return redirect()->action(
            [ProductController::class, 'edit'], ['id' => $productDetail->id]
        );
    }

    public function edit($id) {
        $product = Product::with('category','subcategory')->findOrFail($id);
        $listCategory = Category::whereCategoryType(CategoryType::PRODUCT)->whereStatus(StatusType::PUBLISHED)->get();
        $listBrand = Brand::whereStatus(StatusType::PUBLISHED)->get();
        $varianProduct = ProductVarian::whereProductId($id)->get();
        $media = Media::whereCode($product->media_code)->get();
        $media = $media->sortBy([
            ['media_type', 'DESC']
        ]);
        $video = Media::whereCode($product->media_code)->whereMediaType(MediaType::VIDEO)->first();
        $subCategory = SubCategory::whereStatus(StatusType::PUBLISHED)->whereCategoryId($product->category_id)->get();
        return view('admin.product.edit', compact('product', 'listCategory', 'varianProduct', 'media','subCategory', 'video', 'listBrand'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'title' => 'required|unique:products,title,'.$id,
            'stock' => 'required|numeric|min:0',
            'reseller_price' => 'required|numeric|min:0',
            'customer_price' => 'required|numeric|min:0',
            'catalog_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|lt:catalog_price|min:0',
            'opsi_commision' => 'nullable',
            'point' => 'required|numeric|min:0',
            'point_product' => 'required|numeric|min:1',
            'description' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'commision_rp' => 'required|min:1'
           // 'brand' => 'nullable',
        ]);

        $product = Product::findOrFail($id);
        $product->commision_rp = $request->commision_rp;
        $product->title = $request->title;
      //  $product->brand_id = $request->brand;
        $product->stock = $request->stock;
        $product->reseller_price = $request->reseller_price;
        $product->customer_price = $request->customer_price;
        $product->catalog_price = $request->catalog_price;
        $product->isCommisionRupiah = true;
        $product->discount = $request->discount;
        $product->point = $request->point;
        $product->point_product = $request->point_product;
        $product->description = $request->description;
        $product->slug = Str::slug($request->title);
        $product->category_id = $request->category;
        $product->sub_category_id = $request->subcategory;

        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $product->status = StatusType::PUBLISHED;
            $product->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.product'))->with(['success' => 'Category Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $product->status = StatusType::ARCHIVED;
            $product->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.product'))->with(['success' => 'Category Diperbaharui!']);
        }

        $product->status = StatusType::DRAFT;
        $product->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect()->route('admin.product');

    }

    public function varianSave(Request $request, $id) {
        $isAnyVarian = ProductVarian::whereProductId($id)->first();
        if (!$isAnyVarian) {
            $this->validate($request, [
                'weight' => 'required'
            ]);
        }
        $varianProduct = new ProductVarian();
        $varianProduct->product_id = $id;
        if (!empty($request->color)) {
            $varianProduct->color = $request->color;
            $varianProduct->color_total = $request->color_stock;
        }
        if (!empty($request->weight) && !$isAnyVarian) {
            $varianProduct->weight = $request->weight;
//            $varianProduct->weight_total = $request->weight_stock;
        }
        if (!empty($request->size)) {
            $varianProduct->size = $request->size;
            $varianProduct->size_total = $request->size_stock;
        }
        if (!empty($request->type)) {
            $varianProduct->type = $request->type;
            $varianProduct->type_total = $request->type_stock;
        }
        if (!empty($request->taste)) {
            $varianProduct->taste = $request->taste;
            $varianProduct->taste_total = $request->taste_stock;
        }
        if (!empty($request->color) || !empty($request->weight) || !empty($request->size) || !empty($request->type) || !empty($request->taste)) {
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
            $varianProduct->save();
        } else {
            \Brian2694\Toastr\Facades\Toastr::success('Harap isi minimal 1 varian:)','Success');
        }
        return redirect()->back();
    }

    public function varianEdit($productId, $id) {
        $product = Product::findOrFail($productId);
        $varian = ProductVarian::whereId($id)->first();
        $media = Media::whereCode($product->media_code)->get();
        $media = $media->sortBy([
            ['media_type', 'DESC']
        ]);
        return view('admin.product.edit', compact('varian', 'productId', 'product', 'media'));

    }

    public function varianUpdate(Request $request, $productId, $id) {
        $isAnyVarian = ProductVarian::whereProductId($productId)->first();
//        $this->validate($request, [
//            'weight' => 'required'
//        ]);
        $varianProduct = ProductVarian::findOrFail($id);
        if (!empty($request->color)) {
            $varianProduct->color = $request->color;
            $varianProduct->color_total = $request->color_stock;
        }
        if (!empty($request->weight) && !$isAnyVarian) {
            $varianProduct->weight = $request->weight;
//            $varianProduct->weight_total = $request->weight_stock;
        }
        if (!empty($request->size)) {
            $varianProduct->size = $request->size;
            $varianProduct->size_total = $request->size_stock;
        }
        if (!empty($request->type)) {
            $varianProduct->type = $request->type;
            $varianProduct->type_total = $request->type_stock;
        }
        if (!empty($request->taste)) {
            $varianProduct->taste = $request->taste;
            $varianProduct->taste_total = $request->taste_stock;
        }
        if (!empty($request->color) || !empty($request->weight) || !empty($request->size) || !empty($request->type) || !empty($request->taste)) {
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            $varianProduct->save();
        } else {
            \Brian2694\Toastr\Facades\Toastr::success('Harap isi minimal 1 varian:)','Success');
        }
        return redirect()->action(
            [ProductController::class, 'edit'], ['id' => $productId]
        );
    }

    public function varianDelete($id) {
        $varian = ProductVarian::whereId($id);
        if ($varian->delete()) {
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil dihapus:)','Success');
        }
        return redirect()->back();
    }

    public function uploadImage(Request $request, $productId)
    {
        $product = Product::whereId($productId)->first();
//        $this->validate($request,[
//            'image' => 'image|nullable'
//        ]);
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                $uuid = Uuid::uuid4()->toString();
                $uuid2 = Uuid::uuid4()->toString();
                $fileType = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();
                $filename = $uuid .'-'.$uuid2. '.' . $fileType;
               $path = $file->storeAs('public', $filename);
                Media::create([
                    'file_name' => $filename,
                    'media_type' => MediaType::IMAGE,
                    'file_size' => $fileSize,
                    'code' => $product->media_code,
                    'category_type' => CategoryType::PRODUCT
                ]);
            }
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        } else {
            \Brian2694\Toastr\Facades\Toastr::success('Gagal tambah:)','Gagal');
        }
//        $this->validate($request,[
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
//        ]);
//        $product = Product::whereId($productId)->first();
//        $image = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::PRODUCT, $product->media_code);
//        if (!$image) {
//            \Brian2694\Toastr\Facades\Toastr::success('Gagal tambah:)','Gagal');
//        }
//        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.product.edit', $productId));
    }

    public function uploadVideo(Request $request, $productId) {
        $product = Product::whereId($productId)->first();
        $this->validate($request,[
            'video' => 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:5120'
        ]);
        if ($request->file('video')) {
            $file = $request->file('video');
            $uuid = Uuid::uuid4()->toString();
            $uuid2 = Uuid::uuid4()->toString();
            $fileType = $file->getClientOriginalExtension();
            $filename = $uuid .'-'.$uuid2. '.' . $fileType;
            $path = $file->move('public', $filename);
            $media = Media::create([
                'file_name' => $filename,
                'media_type' => MediaType::VIDEO,
                'code' => $product->media_code,
                'category_type' => CategoryType::PRODUCT
            ]);
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        }
        return redirect(route('admin.product.edit', $productId));
    }

    public function deleteImage($id, $productId) {
        $media = Media::whereId($id)->first();
        File::delete('storage/'.$media->file_name);
        if (!$media->delete()) {
            \Brian2694\Toastr\Facades\Toastr::success('Gagal dihapus:)','Gagal');
        }
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.product.edit', $productId));
    }
}
