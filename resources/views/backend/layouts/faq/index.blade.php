@extends('backend.master')

@push('title')
    FAQs
@endpush

@section('content')
    <div class="page-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="header-title">FAQs</h4>
                <a href="{{ route('admin.faq.create') }}"
                    class="btn btn-primary">Add FAQ</a>
            </div>

            <div class="card-body">
                <table class="table table-striped"
                    id="faqTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            let table = $('#faqTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.faq.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'question'
                    },
                    {
                        data: 'answer'
                    },
                    {
                        data: 'is_active',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '.delete-faq', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Delete FAQ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/faq/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: () => table.ajax.reload()
                        });
                    }
                });
            });
        });
    </script>
@endpush
