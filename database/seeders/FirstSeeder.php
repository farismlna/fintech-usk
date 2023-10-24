<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class FirstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role_id' => 'admin'
        ]);

        User::create([
            'name' => 'Tenizen Bank',
            'username' => 'bank',
            'password' => Hash::make('bank'),
            'role_id' => 'bank'
        ]);

        User::create([
            'name' => 'Tenizen Kantin',
            'username' => 'kantin',
            'password' => Hash::make('kantin'),
            'role_id' => 'kantin'
        ]);

        User::create([
            'name' => 'Faris',
            'username' => 'FarisM',
            'password' => Hash::make('faris'),
            'role_id' => 'siswa'
        ]);

        Student::create([
            'user_id' => 4,
            'nis' => 12336,
            'classroom' => '12 RPL'
        ]);

        Category::create([
            'name' => 'Minuman'
        ]);

        Category::create([
            'name' => 'Makanan'
        ]);

        Category::create([
            'name' => 'Snack'
        ]);

        Product::create([
            'name' => 'Teh Tarik',
            'price' => 3000,
            'stock' => 100,
            'photo' => 'asdasd',
            'description' => 'Es teh tarik',
            'category_id' => 1,
            'stand' => 2
        ]);

        Product::create([
            'name' => 'Nasi Uduk',
            'price' => 10000,
            'stock' => 50,
            'photo' => 'bakso',
            'description' => 'Nasik uduk ponari',
            'category_id' => 2,
            'stand' => 1
        ]);

        Product::create([
            'name' => 'Donut',
            'price' => 3000,
            'stock' => 50,
            'photo' => 'donut',
            'description' => 'Donut warna warni',
            'category_id' => 3,
            'stand' => 1
        ]);

        Wallet::create([
            'user_id' => 4,
            'credit' => 100000,
            'description' => 'Top Up Saldo'
        ]);

        Wallet::create([
            'user_id' => 4,
            'credit' => 15000,
            'description' => 'Biaya Pembukaan Tabungan'
        ]);

        Transaction::create([
            'user_id' => 4,
            'product_id' => 1,
            'status' => 'di keranjang',
            'order_id' => 'INV_12345',
            'price' => 5000,
            'quantity' => 1
        ]);

        Transaction::create([
            'user_id' => 4,
            'product_id' => 2,
            'status' => 'di keranjang',
            'order_id' => 'INV_12345',
            'price' => 10000,
            'quantity' => 1
        ]);

        Transaction::create([
            'user_id' => 4,
            'product_id' => 3,
            'status' => 'di keranjang',
            'order_id' => 'INV_12345',
            'price' => 3000,
            'quantity' => 2
        ]);

        $transactions = Transaction::where('order_id', 'INV_12345')->get();

        $total_debit = 0;

        foreach ($transactions as $transaction) {
            $total_price = $transaction->price * $transaction->quantity;

            $total_debit += $total_price;
        }

        Wallet::create([
            'user_id' => 4,
            'debit' => $total_debit,
            'description' => 'Pembelian Produk'
        ]);

        foreach ($transactions as $transaction) {
            Transaction::find($transaction->id)->update([
                'status' => 'di bayar'
            ]);
        }

        foreach ($transactions as $transaction) {
            Transaction::find($transaction->id)->update([
                'status' => 'di ambil'
            ]);
        }
    }
}
