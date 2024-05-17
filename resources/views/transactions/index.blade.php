@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Transaksi</h1>
    <form method="GET" action="{{ route('transactions.index') }}" class="row g-3">
        <div class="col-md-3">
            <label for="type" class="form-label">Tipe Transaksi</label>
            <select class="form-select" id="type" name="type">
                <option value="">Semua Tipe</option>
                <option value="top_up" {{ request('type') == 'top_up' ? 'selected' : '' }}>Top Up</option>
                <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">Tanggal Akhir</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3">
            <label for="sort" class="form-label">Urutkan Berdasarkan</label>
            <select class="form-select" id="sort" name="sort">
                <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Tanggal (Terbaru)</option>
                <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Tanggal (Terlama)</option>
                <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Jumlah (Tertinggi)</option>
                <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Jumlah (Terendah)</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="group" class="form-label">Kelompokkan Berdasarkan</label>
            <select class="form-select" id="group" name="group">
                <option value="daily" {{ request('group') == 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="monthly" {{ request('group') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="yearly" {{ request('group') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="search" class="form-label">Cari</label>
            <input type="text" class="form-control" id="search" name="search" placeholder="Cari kode atau catatan" value="{{ request('search') }}">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
        </div>
    </form>

    @foreach ($transactions as $group => $groupedTransactions)
        <h3>{{ $group }}</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Bukti</th>
                    {{-- <th>Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_code }}</td>
                        <td>{{ $transaction->type == "top_up" ? "Top Up" : "Transfer" }}</td>
                        <td>Rp{{ number_format(abs($transaction->amount), 2, ",", ".") }}</td>
                        <td>{{ $transaction->notes }}</td>
                        <td>
                            @if ($transaction->proof)
                                <a href="{{ Storage::url("../" . $transaction->proof) }}" target="_blank">Lihat bukti</a>
                            @endif
                        </td>
                        {{-- <td>
                            <a href="{{ route('transactions.edit', $transaction) }}">Edit</a>
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
@endsection
