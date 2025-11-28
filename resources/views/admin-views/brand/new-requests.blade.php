@extends('layouts.admin.app')

@section('title', translate('new_brand_requests'))

@section('content')
<div class="container">
    <h1>New Brand Requests</h1>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Brand Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brandRequests as $request)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $request->brand_name }}</td>
                <td>{{ $request->description }}</td>
                <td>
                    <form action="{{ route('admin.brand.approve', $request->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button class="btn btn-success">Approve</button>
                    </form>
                    <form action="{{ route('admin.brand.reject', $request->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button class="btn btn-danger">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
