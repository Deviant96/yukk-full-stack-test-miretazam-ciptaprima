<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::where('user_id', $user->id);

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_code', 'like', "%{$request->search}%")
                    ->orWhere('notes', 'like', "%{$request->search}%");
            });
        }

        // Filter by transaction type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Sort
        if ($request->has('sort') && $request->sort != '') {
            $sort = explode('_', $request->sort);
            $field = $sort[0];
            $direction = $sort[1];
        
            if ($field == 'created_at' && in_array($direction, ['asc', 'desc'])) {
                $query->orderBy('created_at', $direction);
            } elseif ($field == 'amount' && in_array($direction, ['asc', 'desc'])) {
                $query->orderBy('amount', $direction);
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Group
        $group = $request->get('group', 'daily');
        $transactions = $query->get()->groupBy(function($date) use ($group) {
            if ($group == 'monthly') {
                return Carbon::parse($date->created_at)->format('Y-m');
            } elseif ($group == 'yearly') {
                return Carbon::parse($date->created_at)->format('Y');
            } else {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            }
        });

        return view('transactions.index', compact('transactions', 'group'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:top_up,transaction',
            'amount' => 'required|numeric|min:10000',
            'notes' => 'nullable|string|max:255',
            'proof' => 'nullable|image|max:2048'
        ]);

        $transactionCode = ($request->type == 'top_up' ? 'TU' : 'TR') . strtoupper(uniqid());

        $transaction = new Transaction();
        $transaction->user_id = Auth::id();
        $transaction->transaction_code = $transactionCode;
        $transaction->type = $request->type;
        $transaction->amount = $request->type == 'top_up' ? $request->amount : -$request->amount;
        $transaction->notes = $request->notes;

        if ($request->type == 'top_up' && $request->hasFile('proof')) {
            $path = $request->file('proof')->store('proofs');
            $transaction->proof = $path;
        }

        $transaction->save();

        return redirect()->route('transactions.index')->with('status', 'Transaksi berhasil dibuat.');
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    // public function edit(Transaction $transaction)
    // {
    //     return view('transactions.edit', compact('transaction'));
    // }

    // public function update(Request $request, Transaction $transaction)
    // {
    //     if ($transaction->user_id !== Auth::id()) {
    //         return redirect()->route('transactions.index')->with('error', 'Anda tidak diizinkan menjalankan aksi tersebut.');
    //     }

    //     $request->validate([
    //         'type' => 'required|in:top_up,transaction',
    //         'amount' => 'required|numeric|min:10000',
    //         'notes' => 'nullable|string|max:255',
    //         'proof' => 'nullable|image|max:2048'
    //     ]);

    //     $transaction->type = $request->type;
    //     $transaction->amount = $request->amount;
    //     $transaction->notes = $request->notes;

    //     if ($request->type == 'top_up' && $request->hasFile('proof')) {
    //         $path = $request->file('proof')->store('storage/proofs');
    //         $transaction->proof = $path;
    //     }

    //     $transaction->save();

    //     return redirect()->route('transactions.index')->with('status', 'Transaksi berhasil diperbarui.');
    // }

    // public function destroy(Transaction $transaction)
    // {
    //     if ($transaction->user_id !== Auth::id()) {
    //         return redirect()->route('transactions.index')->with('error', 'Anda tidak diizinkan menjalankan aksi tersebut.');
    //     }

    //     $transaction->delete();

    //     return redirect()->route('transactions.index')->with('status', 'Transaksi berhasil dihapus.');
    // }
}
