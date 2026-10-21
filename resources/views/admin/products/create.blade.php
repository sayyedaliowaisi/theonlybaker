@extends('layouts.admin')

@section('title', 'Add Product | The Only Baker')

@push('styles')
    <link
    rel="stylesheet"
    href="{{ asset('css/admin-products.css') }}?v={{ file_exists(public_path('css/admin-products.css')) ? filemtime(public_path('css/admin-products.css')) : '1' }}"
>
@endpush

@section('content')
<div class="tob-products-admin tob-products-form-page">

    <div class="tob-products-heading">
        <div>
            <a
                class="tob-products-back"
                href="{{ route('admin.products.index') }}"
            >
                ← Back to Products
            </a>

            <h1>Add New Product</h1>

            <p>
                Create a new product for The Only Baker.
            </p>
        </div>
    </div>

    <div class="tob-products-panel tob-products-form-panel">
        <form
            action="{{ route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            @include('admin.products._form')
        </form>
    </div>

</div>
@endsection