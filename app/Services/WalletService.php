<?php

namespace App\Services;

use Exception;
use App\Enums\PaymentMethodsTypeEnum;
use App\Enums\TransactionsStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public $errorMessage;

    function addFunds($request)
    {
        return $this->deposit($request->user_id,$request);

    }
    function transferFunds($request)
    {
        $withdrawalTransaction = $this->withdrawal($request->recipient_id,$request);
        $deposetTransaction = $this->deposit($request->recipient_id,$request);
//        $wallet = Wallet::where('user_id', $request->sender_id)->first();
//        $transaction = new WalletTransaction([
//            'wallet_id' => $wallet->id,
//            'type' => TransactionTypeEnum::withdrawal,
//            'amount' => $request->amount,
//            'sender_id'=> $request->sender_id,
//            'recipient_id'=> $request->recipient_id,
//            'status' => TransactionsStatusEnum::completed,
//            'transaction_date'=>Carbon::now(),
//            'payment_method'=>$request->enum('payment_method', PaymentMethodsTypeEnum::class)
//        ]);
//        $transaction->save();
//
//        $wallet = Wallet::where('user_id', $request->recipient_id)->first();
//
//        $transaction = new WalletTransaction([
//            'wallet_id' => $wallet->id,
//            'type' => TransactionTypeEnum::deposit->value,
//            'amount' => $request->amount,
//            'sender_id'=> $request->sender_id,
//            'recipient_id'=> $request->recipient_id,
//            'status' => TransactionsStatusEnum::completed,
//            'transaction_date'=>Carbon::now(),
//            'payment_method'=>$request->enum('payment_method', PaymentMethodsTypeEnum::class)
//        ]);
//        $transaction->save();

        return $withdrawalTransaction;
    }

    function deposit($wallet_user_id,$request,$reference_id=""){

        $wallet = Wallet::where('user_id', $wallet_user_id)->first();
        $transaction = new WalletTransaction([
            'wallet_id' => $wallet->id,
            'type' => TransactionTypeEnum::deposit->value,
            'amount' => $request->amount,
            'status' => TransactionsStatusEnum::completed,
            'transaction_date'=>Carbon::now(),
            'payment_method'=>$request->enum('payment_method', PaymentMethodsTypeEnum::class),
            'reference_id'=>$reference_id
        ]);
        $transaction->save();
        return $wallet;
    }

    function withdrawal($wallet_user_id,$request){
        $wallet = Wallet::where('user_id', $wallet_user_id)->first();
        $transaction = new WalletTransaction([
            'wallet_id' => $wallet->id,
            'type' => TransactionTypeEnum::withdrawal->value,
            'amount' => $request->amount,
            'status' => TransactionsStatusEnum::completed,
            'transaction_date'=>Carbon::now(),
        ]);
        $transaction->save();
        return $transaction;
    }
}