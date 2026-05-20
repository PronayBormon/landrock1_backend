@extends('backend.master')

@push('title')
    Chat Messages
@endpush

@section('content')
<div class="page-container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="header-title mb-1">Chat Messages</h4>
                        <p class="text-muted mb-0">Participants: {{ $chat->users->pluck('name')->filter()->implode(', ') ?: 'N/A' }}</p>
                    </div>
                    <a href="{{ route('admin.chats.index') }}" class="btn btn-soft-primary btn-sm">Back to Chats</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped dt-responsive nowrap w-100" id="chatMessagesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sender</th>
                                    <th>Message</th>
                                    <th>Type</th>
                                    <th>Sent At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chat->messages->sortBy('created_at') as $index => $message)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $message->sender->name ?? 'System' }}</td>
                                        <td>{{ $message->message }}</td>
                                        <td>{{ ucfirst($message->type ?? 'text') }}</td>
                                        <td>{{ $message->created_at ? $message->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
