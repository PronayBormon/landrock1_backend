@extends('backend.master')

@push('title')
    Subscribers
@endpush

@section('content')
<div class="page-container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                    <h4 class="header-title">Subscribers</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped dt-responsive nowrap w-100"
                            id="subscribersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
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
    let table = $('#subscribersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.subscribers.index') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'email' },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false },
        ]
    });

    // DELETE
    $(document).on('click', '.delete-subscriber', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This subscriber will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/subscribers/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        Swal.fire('Deleted!', 'Subscriber deleted.', 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});
</script>
@endpush