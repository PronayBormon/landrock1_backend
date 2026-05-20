@extends('backend.master')

@push('title')
    Chats
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
                    <h4 class="header-title mb-0">Chats</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped dt-responsive nowrap w-100" id="chatsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Participants</th>
                                    <th>Messages</th>
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
    function showPageLoader() {
        $('#pageLoader').removeClass('d-none');
    }

    function hidePageLoader() {
        $('#pageLoader').addClass('d-none');
    }

    $(function() {
        showPageLoader();

        let table = $('#chatsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.chats.index') }}",
                dataSrc: 'data'
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'participants' },
                { data: 'messages' },
                { data: 'created_at' },
                { data: 'action', orderable: false, searchable: false },
            ],
            drawCallback: function(settings) {
                hidePageLoader();
            }
        });

        $('#chatsTable').on('preXhr.dt', function() {
            showPageLoader();
        });

        $('#chatsTable').on('xhr.dt', function() {
            hidePageLoader();
        });

        $(document).on('click', '.chat-view', function() {
            let id = $(this).data('id');
            window.location.href = "{{ url('/admin/chats') }}" + '/' + id;
        });
    });
</script>
@endpush
