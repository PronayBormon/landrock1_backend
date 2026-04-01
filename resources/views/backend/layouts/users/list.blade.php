@extends('backend.master')

@push('title')
    System Settings
@endpush
@push('styles')
    <!-- dropzone css -->
    <link rel="stylesheet"
        href="/backend/assets/vendor/dropzone/dropzone.css"
        type="text/css" />
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Users</h4>
                        <a href="{{ url('admin.users.create') }}"
                            class="btn btn-soft-primary rounded-pill">New user</a>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive-sm">
                            <table class="table table-bordered mb-0"
                                id="usersTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Avatar</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Join Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <!-- Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- Export dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(function() {
            let table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // dom: "<'row mb-3'<'col-md-6'B><'col-md-6 text-end'f>>rtip",
                // buttons: [{
                //         extend: 'excelHtml5',
                //         text: '<i class="ri-file-excel-2-line"></i> Excel',
                //         className: 'btn btn-success btn-sm',
                //         exportOptions: {
                //             columns: ':not(:last-child)'
                //         }
                //     },
                //     {
                //         extend: 'pdfHtml5',
                //         text: '<i class="ri-file-pdf-line"></i> PDF',
                //         className: 'btn btn-danger btn-sm',
                //         exportOptions: {
                //             columns: ':not(:last-child)'
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         text: '<i class="ri-printer-line"></i> Print',
                //         className: 'btn btn-secondary btn-sm',
                //         exportOptions: {
                //             columns: ':not(:last-child)'
                //         }
                //     }
                // ],
                ajax: {
                    url: "{{ route('admin.users.index') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'avatar',
                        name: 'avatar'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            // Filter
            $('#filter').click(function() {
                table.ajax.reload();
            });

            // Reset
            $('#reset').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                table.ajax.reload();
            });


            /* ================= DELETE WITH SWEETALERT ================= */
            $(document).on('click', '.delete-page', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This page will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/pages/delete/" + id,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function() {
                                Swal.fire('Deleted!', 'Page has been deleted.',
                                    'success');
                                table.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
