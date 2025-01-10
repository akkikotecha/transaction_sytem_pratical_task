<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-white">
            Transaction
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:py-8 ">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <button
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ms-3 mt-3 createTransactionModal"
                        data-bs-toggle="modal" data-bs-target="#createTransactionModal">Add
                        Transaction</button>

                    <table class="table mt-4" id="transactions-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Running Balance</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                    {{ $transactions->links() }}
                    <!-- Pagination links -->
                </div>
                <!-- Modal for creating transaction -->
                <div class="modal fade" id="createTransactionModal" tabindex="-1"
                    aria-labelledby="createTransactionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="transactionForm" data-parsley-validate>
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createTransactionModalLabel">Create Transaction</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select class="form-control" id="type" name="type" data-parsley-required="true">
                                            <option value="">Select</option>
                                            <option value="credit">Credit</option>
                                            <option value="debit">Debit</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            data-parsley-required="true" data-parsley-min="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            data-parsley-required="true" data-parsley-maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Transaction</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
