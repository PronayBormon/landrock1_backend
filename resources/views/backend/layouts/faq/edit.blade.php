@extends('backend.master')

@section('content')
    <div class="page-container">
        <div class="card">
            <div class="card-header">
                <h4>Edit FAQ</h4>
            </div>
            <div class="card-body">

                <form method="POST"
                    action="{{ route('admin.faq.update', $faq->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Question</label>
                        <input type="text"
                            name="question"
                            value="{{ $faq->question }}"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Answer</label>
                        <textarea name="answer"
                            rows="4"
                            class="form-control"
                            required>{{ $faq->answer }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="is_active"
                            class="form-control">
                            <option value="1"
                                @selected($faq->is_active)>Active</option>
                            <option value="0"
                                @selected(!$faq->is_active)>Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">Update FAQ</button>
                </form>

            </div>
        </div>
    </div>
@endsection
