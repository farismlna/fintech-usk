<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function rupiah($saldo) {
        $hasil_rupiah = "Rp " . number_format($saldo,2,',','.');

        return view('home', compact('hasil_rupiah'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->role_id == 'bank') {
            $wallets = Wallet::where('status', 'selesai')->get();
            $credit = 0;
            $debit = 0;

            foreach ($wallets as $key => $wallet) {
                $credit += $wallet->credit;
                $debit += $wallet->debit;
            }

            $saldo = $credit - $debit;

            $nasabah = User::where('role_id', 'siswa')->get()->count();

            $transactions = Transaction::all()->groupBy('order_id')->count();

            $request_topup = Wallet::where('status', 'proses')->get();
            // dd($request_topup);

            return view('home', compact('saldo', 'nasabah', 'transactions', 'request_topup'));
        }

        if (Auth::user()->role_id == 'siswa') {
            $wallets = Wallet::where('user_id', Auth::user()->id)->where('status', 'selesai')->get();
            $credit = 0;
            $debit = 0;

            foreach ($wallets as $key => $wallet) {
                $credit += $wallet->credit;
                $debit += $wallet->debit;
            }

            $saldo = $credit - $debit;

            $products = Product::all();

            $carts = Transaction::where('status', 'di keranjang')->get();

            $total_biaya = 0;

            foreach ($carts as $cart) {
                # code...
                $total_price = $cart->price * $cart->quantity;

                $total_biaya += $total_price;
            }

            $mutasi = Wallet::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();

            $transactions = Transaction::where('status', 'di ambil')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(5)->groupBy('order_id');

            return view('home', compact('saldo', 'products', 'carts', 'total_biaya', 'mutasi', 'transactions'));
        }
    }
}
