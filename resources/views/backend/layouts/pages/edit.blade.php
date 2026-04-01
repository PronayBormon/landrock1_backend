@extends('backend.master')

@push('title')
    Edit Page
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css"
        rel="stylesheet">
@endpush

@push('script')
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script>
        $('#summernote').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-12 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title mb-0">Edit Page</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.pages.update', $page->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label">Page Title</label>
                                <input type="text"
                                    name="title"
                                    value="{{ old('title', $page->title) }}"
                                    class="form-control"
                                    required>
                            </div>

                            <!-- Slug -->
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text"
                                    name="slug"
                                    value="{{ old('slug', $page->slug) }}"
                                    class="form-control"
                                    required>
                            </div>

                            <!-- Status Switch -->
                            <div class="mb-4">
                                <label class="form-label d-block">Status</label>
                                <div>
                                    <input type="checkbox"
                                        id="statusSwitch"
                                        name="is_active"
                                        value="1"
                                        data-switch="success"
                                        {{ $page->is_active ? 'checked' : '' }}>
                                    <label for="statusSwitch"
                                        data-on-label="Active"
                                        data-off-label="Inactive"
                                        class="mb-0 d-block"></label>
                                </div>
                            </div>


                            <div class="mb-4">
                                <label class="form-label d-block">Content</label>
                                <div>
                                    <textarea name="content"
                                        id="summernote"
                                        cols="30"
                                        rows="10"
                                        class="form-control">{{ $page->content }}</textarea>
                                    <label for="summernote"
                                        data-on-label="Active"
                                        data-off-label="Inactive"
                                        class="mb-0 d-block"></label>
                                </div>
                            </div>



                            <!-- Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.pages.index') }}"
                                    class="btn btn-light">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="btn btn-primary">
                                    Update Page
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
