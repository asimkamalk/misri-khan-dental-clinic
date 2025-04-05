{{-- resources/views/admin/services/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add New Service')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
    <style>
        .image-preview {
            max-width: 300px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 3px;
            display: none;
        }

        .note-editor .dropdown-toggle::after {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Add New Service</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label required">Service Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                            id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="is_active" class="form-label d-block">&nbsp;</label>
                        <div class="form-check">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox"
                                id="is_active" name="is_active" value="1"
                                {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="is_active">
                                Service is active
                            </label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="short_description" class="form-label required">Short Description</label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description"
                        name="short_description" rows="2" maxlength="500" required>{{ old('short_description') }}</textarea>
                    <small class="text-muted">Brief description for service cards (max 500 characters)</small>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="long_description" class="form-label required">Detailed Description</label>
                    <textarea class="form-control summernote @error('long_description') is-invalid @enderror" id="long_description"
                        name="long_description" rows="6" required>{{ old('long_description') }}</textarea>
                    @error('long_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Service Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                        name="image" accept="image/*">
                    <small class="text-muted">Recommended size: 800x600 pixels. Max size: 2MB.</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <img id="image-preview" src="#" alt="Image Preview" class="image-preview">
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Service</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Image preview
            $("#image").change(function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $("#image-preview").attr("src", e.target.result).show();
                    };

                    reader.readAsDataURL(this.files[0]);
                } else {
                    $("#image-preview").attr("src", "#").hide();
                }
            });

            // Character counter for short description
            $("#short_description").on("input", function() {
                const maxLength = 500;
                const currentLength = $(this).val().length;
                const remainingChars = maxLength - currentLength;

                if (remainingChars < 0) {
                    $(this).val($(this).val().substring(0, maxLength));
                }
            });
        });
    </script>
@endpush
