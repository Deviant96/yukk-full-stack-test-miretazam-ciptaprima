@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transaksi Baru</h2>
    <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="type" class="form-label">Tipe</label>
            <select class="form-select" id="type" name="type" required>
                <option value="" selected>Pilih Tipe</option>
                <option value="top_up">Top up</option>
                <option value="transaction">Transaksi</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="amount" name="amount" placeholder="Jumlah" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Catatan</label>
            <input type="text" class="form-control" id="notes" name="notes" placeholder="Catatan">
        </div>
        <div class="mb-3" id="proof-section" style="display: none;">
            <label for="proof" class="form-label">Unggah Bukti</label>
            <input type="file" class="form-control" id="proof" name="proof">
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>

<script>
document.body.onload = function() {
    var proofSection = document.getElementById('proof-section');
    var typeInput = document.getElementById('type');
    if (typeInput.value === 'top_up') {
        proofSection.style.display = 'block';
    } else {
        proofSection.style.display = 'none';
    }
}

document.getElementById('type').addEventListener('change', function () {
    var proofSection = document.getElementById('proof-section');
    if (this.value === 'top_up') {
        proofSection.style.display = 'block';
    } else {
        proofSection.style.display = 'none';
    }
});
</script>
@endsection
