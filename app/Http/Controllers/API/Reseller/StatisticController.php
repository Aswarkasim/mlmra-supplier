<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\ResellerTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public static function getResellerId() {
        return Auth::guard('reseller-api')->id();
    }

    public static function getMalmoraTransaction() {
        $transaction = ResellerTransaction::whereTransactionStatus(TransactionStatus::DONE)
            ->whereResellerId(self::getResellerId())->get();
        return $transaction;
    }

    public static function getBerberkahTransaction() {
        $transaction = Transaction::whereTransactionStatus(TransactionStatus::DONE)
            ->whereResellerId(self::getResellerId())->get();
        return $transaction;
    }

    public function total(Request $request) {
        $malmoras = count(self::getMalmoraTransaction());
        $berberkas = count(self::getBerberkahTransaction());

        $totalMalmoraLast =
        $totalMalmoraCurrent =
        $totalBerberkahLast =
        $totalBerberkahCurrent =

        $totalBothLast =
        $totalBothCurrent =

        $selisihBoth =
    }

    public function malmora() {
        return self::getMalmoraTransaction();
    }

    public function berberkah() {
        return self::getBerberkahTransaction();
    }
}
