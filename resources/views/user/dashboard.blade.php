@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Selamat datang, {{ $user->name }}</h2>
            <p>Saldo Anda: Rp{{ number_format($balance, 2, ",", ".") }}</p>

            <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Transaksi Baru</a>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary mb-3">Riwayat Transaksi</a>

            <h3>Transaksi Terbaru</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_code }}</td>
                            <td>Rp{{ number_format($transaction->amount, 2, ",", ".") }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- {{ $transactions->links() }} --}}
        </div>
    </div>
</div>
@endsection
