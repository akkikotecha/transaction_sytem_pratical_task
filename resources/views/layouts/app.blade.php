<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/datatables@1.12.1/media/css/jquery.dataTables.min.css" rel="stylesheet" />
    <!-- Add these in your master layout file (e.g., layouts/app.blade.php) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/src/parsley.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css">
    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    .form-control {
        display: block;
        width: 100%;
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--bs-body-color);
        background-color: var(--bs-form-control-bg);
        background-clip: padding-box;
        border: var(--bs-border-width) solid var(--bs-border-color);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: .375rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .createTransactionModal {
        margin-left: auto !important;
        display: flex;
        justify-content: end;
        margin-right: 15px;
    }

    .dt-paging>nav {
        display: flex;
        justify-content: end;
    }

    .swal2-icon-content {
        font-size: 60px !important;
    }
    .bg-blue-color
    {
        background: linear-gradient(159deg, rgba(0,71,171,1) 0%, rgba(28,169,201,1) 100%);


    }
    .bg-blue-color-light
    {
        background: #0d75b9;
    }
    .w-10
    [
        max-width:10%!important;
    ]

</style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        @isset($header)
        <header class="bg-blue-color-light shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- jQuery (CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/datatables@1.12.1/media/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "2000",
        "hideDuration": "2000",
        "timeOut": "5000", // 10 seconds
        "extendedTimeOut": "5000", // 10 seconds
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
    };
    </script>


    <script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#transactionsTable').DataTable();
    });

    $(document).ready(function() {
        // Initialize Parsley.js on the form
        $('#transactionForm').parsley();

        // Submit the form via AJAX
        $('#transactionForm').on('submit', function(event) {
            event.preventDefault();

            // If form is valid according to Parsley.js
            if ($(this).parsley().isValid()) {
                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('transactions.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            if (response.message ==
                                "Insufficient balance for this transaction.") {
                                toastr.error(response.message);
                            } else {
                                toastr.success(response.message);
                                $('#transactions-table').DataTable().ajax.reload();
                                $('#transactionForm')[0]
                                    .reset(); // Native JS reset method via jQuery
                                $('#createTransactionModal').modal('hide'); // Close modal
                            }

                            // location.reload(); // Reload page to reflect new transaction
                        } else {
                            toastr.error('Failed to add transaction!');
                        }
                    },
                    error: function(xhr) {
                        // Handle server-side validation errors and display them using Toastr
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(function(error) {
                                toastr.error(error[0]);
                            });
                        } else {
                            toastr.error('An unexpected error occurred.');
                        }
                    }
                });
            } else {
                toastr.error('Please correct the errors in the form.');
            }
        });
    });

    $(document).ready(function() {
        $('#transactions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('transactions.data') }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'running_balance',
                    name: 'running_balance'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            dom: "<'row'<'col-sm-6 mt-4'l><'col-sm-6 mt-4'f>>" +
                // Top: Left - Entries dropdown, Right - Search box
                "<'row'<'col-sm-12'tr>>" + // Middle: Table
                "<'row justify-content-between align-items-center'<'col-sm-6 mt-4 mb-4 'i><'col-sm-6 mt-4 mb-4 text-end'p>>",

        });
    });

    function deleteConfirmation(transactionId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form via AJAX
                $.ajax({
                    url: `{{ route('transactions.destroy', ':id') }}`.replace(':id', transactionId),
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Toast Notification
                        toastr.success('Transaction deleted successfully!');

                        // Refresh DataTable
                        $('#transactions-table').DataTable().ajax.reload();
                    },
                    error: function() {
                        Swal.fire('Error!', 'There was an error deleting the transaction.',
                            'error');

                        // Toast Notification for Error
                        toastr.error('Failed to delete the transaction!');
                    }
                });
            }
        });
    }
    </script>
</body>

</html>
