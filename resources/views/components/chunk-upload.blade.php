@props([
    'name',
    'label' => null,
    'value' => null,
    // 'accept' => 'image/*',
    'maxSize' => 100, // MB
])

@php
    $id = 'dz_' . $name . '_' . uniqid();
@endphp

<style>
    .dropzone .dz-preview .dz-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dropzone {
        border: 2px dashed var(--ct-border-color);
        background: var(--ct-secondary-bg);
        border-radius: 8px;
        cursor: pointer;
        min-height: 150px;
        padding: 20px;
    }
</style>

<div class="mb-4 ">
    @if ($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    <div class="dropzone"
        id="{{ $id }}">
        {{-- <i class="ti ti-cloud-upload h1 text-muted"></i> --}}
    </div>

    <input type="hidden"
        name="{{ $name }}"
        id="{{ $id }}_input"
        value="{{ $value }}">

    @if ($value)
        <small class="text-muted d-block mt-1">
            Current file: {{ basename($value) }}
        </small>
    @endif
</div>

@once
    @push('styles')
        <link rel="stylesheet"
            href="/backend/assets/vendor/dropzone/dropzone.css">
    @endpush
@endonce

@once
    @push('script')
        <script src="/backend/assets/vendor/dropzone/dropzone-min.js"></script>
        <script>
            Dropzone.autoDiscover = false;

            window.initChunkDropzone = function(config) {
                new Dropzone('#' + config.id, {
                    url: config.url,
                    paramName: "file",
                    maxFiles: 1,
                    maxFilesize: config.maxSize,
                    chunking: true,
                    forceChunking: true,
                    chunkSize: 1024 * 1024,
                    retryChunks: true,
                    retryChunksLimit: 3,
                    addRemoveLinks: true,
                    parallelChunkUploads: false,
                    headers: {
                        'X-CSRF-TOKEN': config.csrf
                    },

                    init: function() {

                        this.on("success", function(file, response) {
                            document.getElementById(config.input).value = response.path;
                        });

                        this.on("removedfile", function() {
                            document.getElementById(config.input).value = '';
                        });

                        if (config.existing) {
                            const fileName = config.existing.split('/').pop();
                            const ext = fileName.split('.').pop().toLowerCase();
                            const imageExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

                            const mock = {
                                name: fileName,
                                size: 123456,
                                accepted: true
                            };

                            this.emit("addedfile", mock);

                            if (imageExts.includes(ext)) {
                                this.emit("thumbnail", mock, "/" + config.existing);
                            }

                            this.emit("complete", mock);
                            mock.previewElement.classList.add('dz-success', 'dz-complete');
                            this.files.push(mock);
                        }
                    }
                });
            };
        </script>
    @endpush
@endonce

@push('script')
    <script>
        initChunkDropzone({
            id: "{{ $id }}",
            input: "{{ $id }}_input",
            url: "{{ route('admin.upload.chunk') }}",
            csrf: "{{ csrf_token() }}",
            maxSize: {{ $maxSize }},
            existing: @json($value)
        });
    </script>
@endpush
