<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Address;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\CategoryResellerLog;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductReseller;
use App\Models\ProductVarian;
use App\Models\ResellerCart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function detail(Request $request)
    {

        $productDetail = Product::with('user')->whereSlug($request->slug)->first();
        // print_r($productDetail);
        $media = Media::whereCode($productDetail->media_code)->get();
        $category = Category::whereId($productDetail->category_id)->get();
        $sub_category = SubCategory::whereId($productDetail->sub_category_id)->get();
        $user = User::whereId($productDetail->user_id)->get();
        $supplierAdress = Address::whereUserId($productDetail->user_id)->whereStatus(StatusType::ACTIVE)->first();
        $varian_warna = ProductVarian::whereProductId($productDetail->id)->select('id', 'color', 'color_total')->get();
        $varian_berat = ProductVarian::whereProductId($productDetail->id)->select('id', 'weight', 'weight_total')->get();
        $varian_ukuran = ProductVarian::whereProductId($productDetail->id)->select('id', 'size', 'size_total')->get();
        $varian_tipe = ProductVarian::whereProductId($productDetail->id)->select('id', 'type', 'type_total')->get();
        $varian_rasa = ProductVarian::whereProductId($productDetail->id)->select('id', 'taste', 'taste_total')->get();
        $anotherProduct = Product::whereUserId($productDetail->user_id)
            ->where('slug', '!=', $productDetail->slug)
            ->whereStatus(StatusType::PUBLISHED)->get();
        if ($request->has('rating')) {
            $comment = Comment::with('reseller', 'reseller.media')->whereProductId($productDetail->id)->whereRating($request->rating)->get();
        } else {
            $comment = Comment::with('reseller', 'reseller.media')->whereProductId($productDetail->id)->get();
        }
        $ratings = $comment;
        $totalRating = $ratings->count();
        if ($totalRating > 0) {
            $ratingAvg = 0;
            foreach ($ratings as $rate) {
                $ratingAvg += $rate->rating;
            }
            $ratingAvg /= $totalRating;
            $ratingAvg = number_format($ratingAvg, 1, '.', '');
        } else {
            $ratingAvg = [];
        }
        $totalComment = count($comment);
        return response()->json([
            'data' => $productDetail,
            'media' => $media,
            ['varian_warna' => $varian_warna],
            ['varian_berat' => $varian_berat],
            ['varian_ukuran' => $varian_ukuran],
            ['varian_tipe' => $varian_tipe],
            ['varian_rasa' => $varian_rasa],
            'another' => $anotherProduct,
            'category' => $category,
            'sub_category' => $sub_category,
            'supplier' => $user,
            'comment' => $comment,
            'rating_average' => $ratingAvg,
            'alamat_supplier' => $supplierAdress
        ]);
    }

    public function beli(Request $request)
    {
        $productDetail = Product::whereSlug($request->slug)->first();
        $supplierAdress = Address::whereUserId($productDetail->user_id)->whereStatus(StatusType::ACTIVE)->first();
        return response()->json([
            'data' => $productDetail,
            'varian_warna' => $request->varian_color,
            'varian_berat' => $request->varian_weight,
            'varian_ukuran' => $request->varian_ukuran,
            'varian_tipe' => $request->varian_tipe,
            'varian_rasa' => $request->varian_rasa,
            'alamat_supplier' => $supplierAdress
        ]);
    }

    public function allCart()
    {
        $cart = ResellerCart::with(['reseller', 'product'])->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return CartResource::collection($cart);
        // return CartResource::collection($cart);
    }

    public function cart(Request $request)
    {
        $resellerId = Auth::guard('reseller-api')->id();
        $cartCheck = ResellerCart::whereProductId($request->id)
            ->whereResellerId($resellerId)
            ->whereVarianColor($request->varian_color)
            ->whereVarianWeight($request->varian_weight)
            ->whereVarianSize($request->varian_size)
            ->whereVarianType($request->varian_type)
            ->whereVarianTaste($request->varian_taste)->first();
        if ($cartCheck) {
            $cartCheck->order_count += 1;
            if ($request->id && $cartCheck->save()) {
                return response()->json([
                    'status' => "success",
                    'message' => "Berhasil"
                ], 201);
            } else {
                return response()->json([
                    'status' => "failed",
                    'message' => "Gagal"
                ], 401);
            }
        } else {
            $cart = new ResellerCart();
            $cart->reseller_id = $resellerId;
            $cart->product_id = $request->id;
            $cart->order_count += 1;
            $cart->varian_color = $request->varian_color;
            $cart->varian_weight = $request->varian_weight;
            $cart->varian_size = $request->varian_size;
            $cart->varian_type = $request->varian_type;
            $cart->varian_taste = $request->varian_taste;
            if ($request->id && $cart->save()) {
                return response()->json([
                    'status' => "success",
                    'message' => "Berhasil"
                ], 201);
            } else {
                return response()->json([
                    'status' => "failed",
                    'message' => "Gagal"
                ], 401);
            }
        }
    }
    public function incrementOrder(Request $request)
    {
        $resellerId = Auth::guard('reseller-api')->id();
        $cardIsAdded = ResellerCart::whereResellerId($resellerId)->whereId($request->cart)->whereProductId($request->product_id)->first();
        if ($cardIsAdded) {
            $cardIsAdded->order_count += 1;
            $cardIsAdded->save();
            return response()->json([
                'status' => 'success',
                'data' => "jumlah order berhasil diupdate!"
            ], 201);
        } else {
            return response()->json([
                'status' => 'failed',
                'data' => "Product tidak ditemukan~"
            ], 400);
        }
    }

    public function decrementOrder(Request $request)
    {
        $resellerId = Auth::guard('reseller-api')->id();
        $cardIsAdded = ResellerCart::whereResellerId($resellerId)->whereId($request->cart)->whereProductId($request->product_id)->first();
        if ($cardIsAdded) {
            if ($cardIsAdded->order_count == 1) {
                $cardIsAdded->delete();
                return response()->json([
                    'status' => 'success',
                    'data' => "product sudah dihapus"
                ], 201);
            } else {
                $cardIsAdded->order_count -= 1;
                $cardIsAdded->save();
                return response()->json([
                    'status' => 'success',
                    'data' => "jumlah order berhasil diupdate"
                ], 201);
            }
        } else {
            return response()->json([
                'status' => 'failed',
                'data' => "Product tidak ditemukan"
            ], 400);
        }
    }

    public function recomendation(Request $request)
    {
        $product = Product::whereStatus(StatusType::PUBLISHED)
            ->whereCategoryId($request->category_id)
            ->where('id', '!=', $request->id)->get();
        return ProductResource::collection($product);
    }

    public function filterProductByCategory(Request $request)
    {
        $products = Product::whereCategoryId($request->category_id)->get();
        // $products = Product::where('category_id', $request->category_id)->get();
        return ProductResource::collection($products);
    }

    public function filterProductBySubCategory(Request $request)
    {
        $products = Product::where('sub_category_id', $request->sub_category_id)->get();
        return ProductResource::collection($products);
    }

    public function multipleDeleteCart(Request $request)
    {
        for ($i = 0; $i < count($request->data_id); $i++) {
            $cart = ResellerCart::whereId($request->data_id[$i])->first();
            $cart->delete();
        }
        return response()->json([
            'status' => "success",
            'message' => "Berhasil dihapus!"
        ], 201);
    }

    public function addToMyShop(Request $request)
    {
        $category = Product::whereId($request->product_id)->select('category_id')->first();
        $existCategory = CategoryResellerLog::whereCategoryId($category->category_id)->whereResellerId(Auth::guard('reseller-api')->id())->first();
        if (empty($existCategory)) {
            $categoryReseller = new CategoryResellerLog();
            $categoryReseller->reseller_id = Auth::guard('reseller-api')->id();
            $categoryReseller->category_id = $category->category_id;
            $categoryReseller->save();
        }
        $product_reseller = new ProductReseller();
        $product_reseller->reseller_id = Auth::guard('reseller-api')->id();
        $product_reseller->product_id = $request->product_id;
        $product_reseller->category_id = $category->category_id;
        if ($product_reseller->save()) {
            return response()->json([
                'status' => "success",
                'message' => "Berhasil ditambahkan ke toko!"
            ], 201);
        }
    }
}
