@extends('admin::admin.layouts.master')

@section('title', 'Banners Management')

@section('page-title', 'Banner Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('admin.banners.index') }}">Banner Manager</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Banner Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Header with Back button -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $banner->title ?? 'N/A' }} - Banner</h4>
                            <div>
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Banner Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Banner Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Image:</label>
                                            <div>
                                                @if ($banner->image)
                                                    <img src="{{ asset('storage/' . $banner->image) }}"
                                                        alt="{{ $banner->title }}" class="img-fluid rounded border"
                                                        style="max-width: 250px; max-height: 150px;">
                                                @else
                                                    <p>N/A</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Title:</label>
                                                    <p>{{ $banner->title ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Sub Title:</label>
                                                    <p>{{ $banner->sub_title ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Button Title:</label>
                                                    <p>{{ $banner->button_title ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Button URL:</label>
                                                    <p>{{ $banner->button_url ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold">Description:</label>
                                            <p>{!! $banner->description ?? 'N/A' !!}</p>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status:</label>
                                                    <p>{!! config('banner.constants.aryStatusLabel.' . $banner->status, 'N/A') !!}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Created At:</label>
                                                    <p>
                                                        {{ $banner->created_at
                                                            ? $banner->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                            : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            @admincan('banners_manager_edit')
                                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                                    class="btn btn-warning mb-2">
                                                    <i class="mdi mdi-pencil"></i> Edit Banner
                                                </a>
                                            @endadmincan

                                            @admincan('banners_manager_delete')
                                                <button type="button" class="btn btn-danger delete-btn delete-record"
                                                    title="Delete this record"
                                                    data-url="{{ route('admin.banners.destroy', $banner) }}"
                                                    data-redirect="{{ route('admin.banners.index') }}"
                                                    data-text="Are you sure you want to delete this record?"
                                                    data-method="DELETE">
                                                    <i class="mdi mdi-delete"></i> Delete Banner
                                                </button>
                                            @endadmincan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
@endsection
