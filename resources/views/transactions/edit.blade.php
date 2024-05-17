@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Transaction</h1>
        <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <label>Type</label>
                <select name="type">
                    <option value="top_up" {{ $transaction->type == 'top_up' ? 'selected' : '' }}>Top Up</option>
                    <option value="transaction" {{ $transaction->type == 'transaction' ? 'selected' : '' }}>Transaksi</option>
                </select>
            </div>
            <div>
                <label>Amount</label>
                <input type="number" name="amount" step="0.01" value="{{ $transaction->amount }}">
            </div>
            <div>
                <label>Notes</label>
                <textarea name="notes">{{ $transaction->notes }}</textarea>
            </div>
            <div>
                <label>Proof</label>
                <input type="file" name="proof">
                @if ($transaction->proof)
                    <a href="{{ Storage::url($transaction->proof) }}" target="_blank">View Proof</a>
                @endif
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
@endsection
