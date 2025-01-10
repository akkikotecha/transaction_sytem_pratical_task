<?php

// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = Transaction::with('user')->paginate(10);
        // return $transactions;
        return view('transactions.index', compact('transactions'));
    }

    public function getTransactionsData(Request $request, TransactionService $transactionService)
    {
        $authUserId = auth()->id();

        // Extract DataTables parameters
        $search = $request->input('search.value');
        $start = $request->input('start');
        $length = $request->input('length');

        // Get transactions data from the service
        $result = $transactionService->getTransactionsData($authUserId, $search, $start, $length);

        // Format data for DataTables
        $data = [];
        foreach ($result['transactions'] as $index => $transaction) {
            $data[] = [
                'DT_RowIndex' => str_pad($start + $index + 1, 3, '0', STR_PAD_LEFT),
                'user_name' => $transaction->user->name ?? 'No User',
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'running_balance' => $transaction->running_balance,
                'description' => $transaction->description,
                'actions' => '<button type="button" class="btn btn-danger btn-sm" onclick="deleteConfirmation(' . $transaction->id . ')"><i class="fa fa-close"></i>Delete</button>',
            ];
        }

        // Return the response
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $result['totalData'],
            "recordsFiltered" => $result['totalData'],
            "data" => $data
        ]);
    }
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        try {

            $userId = auth()->id(); // Get the authenticated user's ID

            // Get the running balance for the current user
            $runningBalance = $this->transactionService->getRunningBalance($userId);

            // Check if it's a debit operation and validate running balance
            if ($request->type === 'debit' && $runningBalance < $request->amount) {
                return response()->json([
                    'success' => true,
                    'message' => 'Insufficient balance for this transaction.',
                ], 201); // 422 Unprocessable Entity
            }
            // Call the service to create a transaction
            $transaction = $this->transactionService->createTransaction($request->all());

            // Return a success response as JSON
            return response()->json([
                'success' => true,
                'message' => 'Transaction added successfully!',
                'data' => $transaction
            ], 201); // 201 Created
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to add transaction',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }


    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function destroy($id)
    {
        // Find the transaction by ID
        $transaction = Transaction::findOrFail($id);

        // Get the latest running balance before the deletion
        $latestBalance = Transaction::orderBy('id', 'desc')->value('running_balance');

        // Adjust the running balance
        if ($transaction->type === 'credit') {
            $newBalance = $latestBalance - $transaction->amount;
        } elseif ($transaction->type === 'debit') {
            $newBalance = $latestBalance + $transaction->amount;
        }

        // Call the service method to adjust subsequent running balances
        $this->transactionService->adjustRunningBalancesAfterDeletion($transaction->id, $newBalance);

        // Delete the transaction
        $transaction->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Transaction deleted successfully!');
    }
}