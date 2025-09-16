@extends('admin::admin.layouts.master')

@section('title', 'Banners Management')

@section('page-title', isset($banner) ? 'Edit Banner' : 'Create Banner')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.banners.index') }}">Banner Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ isset($banner) ? 'Edit Banner' : 'Create Banner' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Banner Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form
                        action="{{ isset($banner) ? route('admin.banners.update', $banner->id) : route('admin.banners.store') }}"
                        method="POST" id="bannerForm" enctype="multipart/form-data">
                        @if (isset($banner))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title<span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ $banner?->title ?? old('title') }}" required>
                                    @error('title')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sub Title<span class="text-danger">*</span></label>
                                    <input type="text" name="sub_title" class="form-control"
                                        value="{{ $banner?->sub_title ?? old('sub_title') }}" required>
                                    @error('sub_title')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button Title<span class="text-danger">*</span></label>
                                    <input type="text" name="button_title" class="form-control"
                                        value="{{ $banner?->button_title ?? old('button_title') }}" required>
                                    @error('button_title')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button URL<span class="text-danger">*</span></label>
                                    <input type="text" name="button_url" class="form-control"
                                        value="{{ $banner?->button_url ?? old('button_url') }}" required>
                                    @error('button_url')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sort Order<span class="text-danger">*</span></label>
                                    <input type="text" name="sort_order" class="form-control numbers-only"
                                        value="{{ $banner?->sort_order ?? old('sort_order') }}" required>
                                    @error('sort_order')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control select2" required>
                                        @foreach (config('banner.constants.status', []) as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ (isset($banner) && (string) $banner?->status === (string) $key) || old('status') === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Image<span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control" id="imageInput"
                                        {{ isset($banner) ? '' : 'required' }}>
                                    @error('image')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div id="imagePreview">
                                        @if (isset($banner) && $banner->image)
                                            <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image"
                                                class="img-thumbnail" style="max-width: 200px; max-height: 120px;">
                                        @else
                                            <img src="{{ asset('images/noimage.png') }}" alt="category Image"
                                                class="img-thumbnail" style="max-width: 200px; max-height: 120px;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description<span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control description-editor">{{ $banner?->description ?? old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"
                                id="saveBtn">{{ isset($banner) ? 'Update' : 'Save' }}</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End banner Content -->
    </div>
@endsection

@push('scripts')
    <!-- Initialize CKEditor -->
    <script>
        $(document).ready(function() {
            $('#description').summernote({
                height: 250, // ✅ editor height
                minHeight: 250,
                maxHeight: 250,
                toolbar: [
                    // ✨ Add "code view" toggle button
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']] // ✅ source code button
                ],
                callbacks: {
                    onChange: function(contents, $editable) {
                        // keep textarea updated
                        $('#description').val(contents);
                        // trigger validation if needed
                        $('#description').trigger('keyup');
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            $.validator.addMethod(
                "alphabetsOnly",
                function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                },
                "Please enter letters only"
            );

            //jquery validation for the form
            $('#bannerForm').validate({
                ignore: [],
                rules: {
                    title: {
                        required: true,
                        minlength: 3,
                    },
                    sub_title: {
                        required: true,
                        minlength: 3,
                    },
                    button_title: {
                        required: true,
                        minlength: 3,
                    },
                    button_url: {
                        required: true,
                        minlength: 3
                    },
                    sort_order: {
                        required: true,
                        digits: true,
                        min: 1,
                    },
                    description: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    title: {
                        required: "Please enter a title",
                        minlength: "Title must be at least 3 characters long"
                    },
                    sub_title: {
                        required: "Please enter a sub title",
                        minlength: "Sub title must be at least 3 characters long"
                    },
                    button_title: {
                        required: "Please enter a button title",
                        minlength: "Button title must be at least 3 characters long"
                    },
                    button_url: {
                        required: "Please enter a button URL",
                        minlength: "Button URL must be at least 3 characters long"
                    },
                    sort_order: {
                        required: "Please enter a sort order",
                        digits: "Sort order must be a valid number",
                        min: "Sort order must be at least 0"
                    },
                    description: {
                        required: "Please enter description",
                        minlength: "Description must be at least 3 characters long"
                    },
                    image: {
                        required: "Image is required"
                    }
                },
                submitHandler: function(form) {
                    // Update textarea before submit
                    $('#description').val($('#description').summernote('code'));

                    const $btn = $('#saveBtn');
                    if ($btn.text().trim().toLowerCase() === 'update') {
                        $btn.prop('disabled', true).text('Updating...');
                    } else {
                        $btn.prop('disabled', true).text('Saving...');
                    }
                    // Now submit
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide(); // hide blade errors
                    if (element.attr("id") === "description") {
                        error.insertAfter($('.note-editor'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            // Image preview logic
            $('#imageInput').on('change', function(event) {
                const input = event.target;
                const preview = $('#imagePreview');
                preview.empty(); // Remove old image

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.html('<img src="' + e.target.result +
                            '" class="img-thumbnail style="max-width:200px; max-height:120px;" />');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
        });
    </script>
@endpush
