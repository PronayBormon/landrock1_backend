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
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Pages</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped dt-responsive nowrap w-100 dataTable dtr-inline"
                                id="pagesTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Created At</th>
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
    <script>
        $(function() {
            let table = $('#pagesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.pages.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'slug'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
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
