@extends('layouts.admin')

@section('title', 'Edit Product | The Only Baker')

@push('styles')
    <link
    rel="stylesheet"
    href="{{ asset('css/admin-product.css') }}?v={{ file_exists(public_path('css/admin-product.css')) ? filemtime(public_path('css/admin-product.css')) : '1' }}"
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

            <h1>Edit Product</h1>

            <p>{{ $product->name }}</p>
        </div>
    </div>

    <div class="tob-products-panel tob-products-form-panel">
        <form
            action="{{ route('admin.products.update', $product) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            @include('admin.products._form')
        </form>
    </div>

</div>
@endsection