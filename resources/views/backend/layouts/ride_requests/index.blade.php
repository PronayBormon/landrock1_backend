@extends('backend.master')

@push('title')
    Ride Requests List
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0">Ride Requests</h4>
                        <a href="{{ route('admin.ride-requests.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Add Ride Request
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="rideRequestsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Travel Date</th>
                                        <th>Preferred Time</th>
                                        <th>Seats</th>
                                        <th>Status</th>
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
            let table = $('#rideRequestsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.ride-requests.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name: 'user.name'
                    },
                    {
                        data: 'from_location',
                        name: 'from_location'
                    },
                    {
                        data: 'to_location',
                        name: 'to_location'
                    },
                    {
                        data: 'travel_date',
                        name: 'travel_date'
                    },
                    {
                        data: 'preferred_time',
                        name: 'preferred_time'
                    },
                    {
                        data: 'seats_needed',
                        name: 'seats_needed'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // SweetAlert2 Delete Action
            $(document).on('click', '.delete-ride-request', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this ride request!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('/admin/ride-requests/delete') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.ajax.reload();
                                    Swal.fire(
                                        'Deleted!',
                                        response.message || 'Ride request has been deleted.',
                                        'success'
                                    );
                                }
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete ride request.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
