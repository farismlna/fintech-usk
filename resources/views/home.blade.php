@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (Auth::user()->role_id == 'bank')
            <div class="col-md-12 mb-3">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header fw-bold" style="font-size: 30px">
                                Saldo
                            </div>
                            <div class="card-body">
                                {{ $saldo }}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                Nasabah
                            </div>
                            <div class="card-body">
                                {{ $nasabah }}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                Transaksi
                            </div>
                            <div class="card-body">
                                {{ $transactions }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        Request Top Up
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($request_topup as $request)
                                <div class="col">
                                    <form action="{{ route('acceptRequest') }}" method="POST" name="id">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $request->id }}">
                                        <div class="card">
                                            <div class="card-header">
                                                {{ $request->user->name }}
                                            </div>
                                            <div class="card-body">
                                                Nominal : {{ $request->credit }}
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-success" type="submit">Acc Req</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::user()->role_id == 'siswa')
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card mb-3">
                    <div class="card-header bg-warning"> Welcome, {{Auth::user()->name}} </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col d-flex justify-content-start align-items-center">
                                Saldo: {{ $saldo }}
                            </div>
                            <div class="col d-flex justify-content-end align-items-center">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formTopUp">
                                    Top Up
                                </button>

                                <!-- Modal -->
                                <form action="{{ route('topupNow') }}" method="POST">
                                    @csrf
                                    <div class="modal fade" id="formTopUp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Masukkan Nominal Top Up</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <input type="number" name="credit" class="form-control" min="10000" value="10000">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Top Up</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-primary">Katalog Produk</div>

                    <div class="card-body">
                        <div class="row">
                            @foreach ($products as $key => $product)
                                <div class="col">
                                    <form action="{{ route('addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                                        <input type="hidden" value="{{ $product->id}}" name="product_id">
                                        <input type="hidden" value="{{ $product->price }}" name="price">

                                        <div class="card">
                                            <div class="card-header bg-success">
                                                {{$product->name}}
                                            </div>
                                            <div class="card-body">
                                                <img src="{{$product->photo}}" alt="">
                                                <div>{{$product->description}}</div>
                                                <div>Harga: {{ $product->price }}</div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="mb-3">
                                                    <input type="number" name="quantity" value="0" min="0" class="form-control">
                                                </div>
                                                <div class="d grid gap-2">
                                                    <button type="submit" class="btn btn-primary">Masukkan Keranjang</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-info">
                        Keranjang
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($carts as $key => $cart)
                                <li>{{ $cart->product->name }} | {{ $cart->quantity }} * {{ $cart->price }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer">
                        Total biaya : {{ $total_biaya }}
                        <form action="{{ route('payNow') }}" method="POST">
                            <div class="d-grid gap-2">
                                @csrf
                                <button type="submit" class="btn btn-success">Bayar Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header bg-info">
                        Mutasi Wallet
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($mutasi as $data)
                                <li>{{ $data->credit ? $data->credit : 'Debit' }} | {{ $data->debit ? $data->debit : 'Kredit'}} |
                                    {{ $data->description }} <span class="badge text-bg-warning">{{ $data->status == 'proses' ? 'PROSES' : ''}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="card-header">
                        Riwayat Transaksi
                    </div>
                    <div class="card-body">
                        @foreach ($transactions as $key => $transaction)
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="row">
                                        <div class="col fw-bold">
                                            {{ $transaction[0]->order_id }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-secondary" style="font-size: 12px">
                                            {{ $transaction[0]->created_at }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col d-flex justify-content-end align-items-center">
                                    <a class="btn btn-success" target="_blank" href="{{ route('download', ['order_id' => $transaction[0]->order_id]) }}">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
