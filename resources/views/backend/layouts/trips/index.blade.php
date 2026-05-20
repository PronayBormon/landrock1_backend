@extends('backend.master')

@push('title')
    Trips List
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Trips</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped dt-responsive nowrap w-100" id="pagesTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Publisher</th>
                                        <th>Status</th>
                                        <th>Ride Date</th>
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

            $('#pagesTable').DataTable({
                processing: true,
                serverSide: true,

                ajax: "{{ route('admin.trips.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'publisher',
                        name: 'publisher'
                    },
                    {
                        data: 'ride_status',
                        name: 'ride_status'
                    },
                    {
                        data: 'ride_date',
                        name: 'ride_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

        });
    </script>
@endpush
