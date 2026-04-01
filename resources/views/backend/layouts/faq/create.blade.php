@extends('backend.master')

@section('content')
    <div class="page-container">
        <div class="card">
            <div class="card-header">
                <h4>Add FAQ</h4>
            </div>
            <div class="card-body">

                <form method="POST"
                    action="{{ route('admin.faq.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Question</label>
                        <input type="text"
                            name="question"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Answer</label>
                        <textarea name="answer"
                            rows="4"
                            class="form-control"
                            required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="is_active"
                            class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">Save FAQ</button>
                </form>

            </div>
        </div>
    </div>
@endsection
