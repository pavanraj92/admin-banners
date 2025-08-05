@extends('admin::admin.layouts.master')

@section('title', 'Banners Management')

@section('page-title', 'Banner Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Banner Manager</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Banner Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.banners.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Keyword</label>
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="{{ app('request')->query('keyword') }}" placeholder="Enter title, sub title">                                   
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">All</option>
                                    <option value="0" {{ app('request')->query('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                    <option value="1" {{ app('request')->query('status') == '1' ? 'selected' : '' }}>Active</option>
                                </select>                                   
                            </div>
                        </div>
                        <div class="col-auto mt-1 text-right">
                            <div class="form-group">
                                <label for="created_at">&nbsp;</label>
                                <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary mt-4">Reset</a>
                            </div>
                        </div>
                    </div>                        
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="card-title">Manage banners</h4> --}}
                    @admincan('banners_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary mb-3">Create New Banner</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S. No.</th>
                                    <th scope="col">@sortablelink('title', 'Title', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">@sortablelink('sub_title', 'Sub Title', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">@sortablelink('button_title', 'Button Title', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($banners) && $banners->count() > 0)
                                @php
                                $i = ($banners->currentpage() - 1) * $banners->perpage() + 1;
                                @endphp
                                @foreach ($banners as $banner)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $banner->title }}</td>
                                    <td>{{ $banner->sub_title }}</td>
                                    <td>{{ $banner->button_title }}</td>
                                    <td>
                                        <!-- create update status functionality-->
                                        @if ($banner->status == '1')
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                title="Click to change status to inactive"
                                                data-url="{{ route('admin.banners.updateStatus') }}"
                                                data-method="POST" data-status="0" data-id="{{ $banner->id }}"
                                                class="btn btn-success btn-sm update-status">Active</a>
                                        @else
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                title="Click to change status to active"
                                                data-url="{{ route('admin.banners.updateStatus') }}"
                                                data-method="POST" data-status="1"
                                                data-id="{{ $banner->id }}"
                                                class="btn btn-warning btn-sm update-status">InActive</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $banner->created_at
                                                        ? $banner->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                        : '—' }}
                                    </td>
                                    <td style="width: 10%;">
                                        @admincan('banners_manager_view')
                                        <a href="{{ route('admin.banners.show', $banner) }}" 
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('banners_manager_edit')
                                        <a href="{{ route('admin.banners.edit', $banner) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan                                                   
                                        @admincan('banners_manager_delete')
                                        <a href="javascript:void(0)" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            title="Delete this record" 
                                            data-url="{{ route('admin.banners.destroy', $banner) }}"
                                            data-text="Are you sure you want to delete this record?"                                                    
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record" ><i class="mdi mdi-delete"></i></a>
                                        @endadmincan
                                        
                                    </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="text-center">No records found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <!--pagination move the right side-->
                        @if ($banners->count() > 0)
                        {{ $banners->links('admin::pagination.custom-admin-pagination') }}
                        @endif

                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- End banner Content -->
</div>
<!-- End Container fluid  -->
@endsection