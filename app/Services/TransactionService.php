<?php

// app/Services/TransactionService.php
namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    public function createTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Get the authenticated user's ID
            $authenticatedUserId = Auth::id();

            // Check if the authenticated user exists (optional validation)
            $user = User::where('id', $authenticatedUserId)->firstOrFail();

            $lastTransaction = Transaction::where('user_id', $authenticatedUserId)->latest()->first();
            $currentBalance = $lastTransaction ? $lastTransaction->running_balance : 0;

            $newBalance = $data['type'] === 'credit'
                ? $currentBalance + $data['amount']
                : $currentBalance - $data['amount'];

            $transaction = Transaction::create([
                'user_id' => $authenticatedUserId, // Use the authenticated user's ID
                'type' => $data['type'],
                'amount' => $data['amount'],
                'running_balance' => $newBalance,
                'description' => $data['description'],
            ]);

            return $transaction;
        });
    }

    public function getRunningBalance($userId)
    {
        // Calculate the running balance dynamically for a specific user
        return Transaction::where('user_id', $userId)
            ->sum(DB::raw("CASE WHEN type = 'credit' THEN amount ELSE -amount END"));
    }

    public function adjustRunningBalancesAfterDeletion($transactionId, $newBalance)
    {
        // Update all subsequent transactions' running balances
        Transaction::where('id', '>', $transactionId)->update([
            'running_balance' => DB::raw("running_balance + ($newBalance - running_balance)")
        ]);
    }

    public function getTransactionsData($authUserId, $search, $start, $length)
    {
        // Query transactions with a basic search filter
        $query = Transaction::with('user')
            ->where('user_id', $authUserId)
            ->when($search, function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });

        // Total records count
        $totalData = $query->count();

        // Fetch paginated data
        $transactions = $query->offset($start)
            ->limit($length)
            ->get();

        return [
            'totalData' => $totalData,
            'transactions' => $transactions
        ];
    }
}