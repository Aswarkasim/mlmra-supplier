<?php

namespace App\Http\Controllers\API\Customer;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CustomerCartResource;
use App\Http\Resources\ProductResource;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CategoryResellerLog;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductReseller;
use App\Models\ProductVarian;
use App\Models\ResellerCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCustomer extends Controller
{
    public function detail(Request $request)
    {
        $productDetail = Product::with('user')->whereId($request->id)->first();
        // print_r($productDetail);
        // die();
        $productResellerId = ProductReseller::whereId($request->id)->first();
        $resellerId = $productResellerId->reseller_id;
        $media = Media::whereCode($productDetail->media_code)->get();
        $supplierAdress = Address::whereUserId($productDetail->user_id)->whereStatus(StatusType::ACTIVE)->first();
        $varian_warna = ProductVarian::whereProductId($productDetail->id)->select('id', 'color', 'color_total')->get();
        $varian_berat = ProductVarian::whereProductId($productDetail->id)->select('id', 'weight', 'weight_total')->get();
        $varian_ukuran = ProductVarian::whereProductId($productDetail->id)->select('id', 'size', 'size_total')->get();
        $varian_tipe = ProductVarian::whereProductId($productDetail->id)->select('id', 'type', 'type_total')->get();
        $varian_rasa = ProductVarian::whereProductId($productDetail->id)->select('id', 'taste', 'taste_total')->get();
        $productReseller = ProductReseller::where('product_id', '!=', $request->id)->whereResellerId($resellerId)->get();
        $resellerProductId = [];
        foreach ($productReseller as $product) {
            array_push($resellerProductId, $product->product_id);
        }
        $anotherProduct = [];
        for ($i = 0; $i < count($resellerProductId); $i++) {
            array_push($anotherProduct, Product::where('id', $resellerProductId[$i])->whereStatus(StatusType::PUBLISHED)->first());
        }
        //$anotherProduct = Product::whereIn('id', $resellerProductId)->whereStatus(StatusType::PUBLISHED)->get();
        if ($request->has('rating')) {
            $comment = Comment::with('customer', 'customer.media')->whereProductId($productDetail->id)->whereRating($request->rating)->get();
        } else {
            $comment = Comment::with('customer', 'customer.media')->whereProductId($productDetail->id)->get();
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
            'comment' => $comment,
            'rating_average' => $ratingAvg,
            'alamat_supplier' => $supplierAdress
        ]);
    }

    public function beli(Request $request)
    {
        $productDetail = Product::whereId($request->id)->first();
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
        $cart = Cart::with(['customer', 'product_reseller'])->whereCustomerId(Auth::guard('customer-api')->id())->get();
        return CustomerCartResource::collection($cart);
    }

    public function cart(Request $request)
    {
        $customerId = Auth::guard('customer-api')->id();
        $cartCheck = Cart::whereProductResellerId($request->product_reseller_id)
            ->whereCustomerId($customerId)
            ->whereVarianColor($request->varian_color)
            ->whereVarianWeight($request->varian_weight)
            ->whereVarianSize($request->varian_size)
            ->whereVarianType($request->varian_type)
            ->whereVarianTaste($request->varian_taste)->first();
        if ($cartCheck) {
            $cartCheck->order_count += 1;
            if ($request->product_reseller_id && $cartCheck->save()) {
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
            $cart = new Cart();
            $cart->customer_id = $customerId;
            $cart->product_reseller_id = $request->product_reseller_id;
            $cart->order_count += 1;
            $cart->varian_color = $request->varian_color;
            $cart->varian_weight = $request->varian_weight;
            $cart->varian_size = $request->varian_size;
            $cart->varian_type = $request->varian_type;
            $cart->varian_taste = $request->varian_taste;
            if ($request->product_reseller_id && $cart->save()) {
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
        $customerId = Auth::guard('customer-api')->id();
        $cardIsAdded = Cart::whereCustomerId($customerId)->whereId($request->cart_id)->whereProductResellerId($request->product_reseller_id)->first();
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
        $customerId = Auth::guard('customer-api')->id();
        $cardIsAdded = Cart::whereCustomerId($customerId)->whereId($request->cart_id)->whereProductResellerId($request->product_reseller_id)->first();
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
        return ProductResource::collection($products);
    }

    public function multipleDeleteCart(Request $request)
    {
        for ($i = 0; $i < count($request->data_id); $i++) {
            $cart = Cart::whereId($request->data_id[$i])->first();
            $cart->delete();
        }
        return response()->json([
            'status' => "success",
            'message' => "Berhasil dihapus!"
        ], 201);
    }

    public function addToMyShop(Request $request)
    {
        $product_reseller = new ProductReseller();
        $product_reseller->reseller_id = Auth::guard('reseller-api')->id();
        $product_reseller->product_id = $request->product_id;
        $category = Product::whereId($request->product_id)->select('category_id')->first();
        $existCategory = CategoryResellerLog::whereCategoryId($category->category_id)->whereResellerId(Auth::guard('reseller-api')->id())->first();
        if (empty($existCategory)) {
            $categoryReseller = new CategoryResellerLog();
            $categoryReseller->reseller_id = Auth::guard('reseller-api')->id();
            $categoryReseller->category_id = $category->category_id;
            $categoryReseller->save();
        }
        if ($product_reseller->save()) {
            return response()->json([
                'status' => "success",
                'message' => "Berhasil ditambahkan ke toko!"
            ], 201);
        }
    }
}
