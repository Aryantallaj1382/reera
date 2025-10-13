<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function balance()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        return api_response([
            'balance' => $wallet->balance,
        ]);
    }
    public function index()
    {
        $user = auth()->user();
        $transactions = Transaction::whereRelation('wallet','user_id', $user->id)->paginate();
        $transactions->getCollection()->transform(function ($transaction) {
            return [
                'id' => $transaction->id,
                'amount' => (int)$transaction->amount,
                'type' => $transaction->operation_type,
                'status' => $transaction-> status,
                'date' => $transaction->created_at->format('Y-m-d'),
                'time' => $transaction->created_at->format('H:i'),
            ];
        });
        return api_response($transactions);

    }
    public function withdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'card_number' => 'required',
        ]);
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        $t = Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'card_number' => $request->card_number,
            'operation_type' => 'withdrawal',
            'status' => 'pending',
        ]);


        return api_response([], 'درخواست شما ثبت شد و بزودی مبلغ به حساب شما واریز میشود');
    }
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required',
        ]);
        $amount  = $request->amount;
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        $t = Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'status' => 'pending',
            'operation_type' => 'deposit',
        ]);
        $callbackUrl_input = '';
        $response = zarinpal()
            ->merchantId(config('zarinpal.merchant_id'))
            ->amount($amount)
            ->request()
            ->description($description ?? 'پرداخت')
            ->callbackUrl($callbackUrl_input)
            ->send();
        if (!$response->success()) {
            throw new \Exception($response->error()->message());
        }
        Payment::create([
            'user_id' => auth()->id(),
            'amount' => $amount,
            'authority' => $response->authority(),
            'status' => 'pending',
            'transaction_id' => $t->id,
        ]);

        return [
            'url' => 'https://payment.zarinpal.com/pg/StartPay/' . $response->authority(),
            'authority' => $response->authority()

        ];
    }

    public function callback(Request $request)
    {
        $response = zarinpal()
            ->amount(10000)
            ->verification()
            ->authority($request->Authority)
            ->send();

        if ($response->success()) {
            $refId = $response->referenceId();
            return "پرداخت موفق بود. کد رهگیری: " . $refId;
        }

        return "پرداخت ناموفق بود!";
    }
}
