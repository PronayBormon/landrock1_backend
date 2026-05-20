@extends('backend.master')

@push('title')
    Bookings
@endpush

@section('content')
<div class="page-container position-relative">
    <div id="pageLoader" class="position-fixed top-0 start-0 w-100 h-100 d-none bg-dark bg-opacity-25 d-flex align-items-center justify-content-center" style="z-index: 2000;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Bookings</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped dt-responsive nowrap w-100" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Trip</th>
                                    <th>Seats</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Booked At</th>
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
    function showPageLoader() {
        $('#pageLoader').removeClass('d-none');
    }

    function hidePageLoader() {
        $('#pageLoader').addClass('d-none');
    }

    $(function() {
        showPageLoader();

        let table = $('#bookingsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.bookings.index') }}",
                dataSrc: 'data'
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user' },
                { data: 'trip' },
                { data: 'seat_count' },
                { data: 'total_price' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'created_at' },
                { data: 'action', orderable: false, searchable: false },
            ],
            drawCallback: function(settings) {
                hidePageLoader();
            }
        });

        $('#bookingsTable').on('preXhr.dt', function() {
            showPageLoader();
        });

        $('#bookingsTable').on('xhr.dt', function() {
            hidePageLoader();
        });

        $(document).on('click', '.delete-booking', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This booking will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showPageLoader();
                    $.ajax({
                        url: "/admin/bookings/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            Swal.fire('Deleted!', 'Booking deleted.', 'success');
                            table.ajax.reload();
                        },
                        complete: function() {
                            hidePageLoader();
                        }
                    });
                }
            });
        });

        $(document).on('click', '.booking-edit', function() {
            let id = $(this).data('id');
            window.location.href = "{{ url('/admin/bookings/edit') }}" + '/' + id;
        });
    });
</script>
@endpush
