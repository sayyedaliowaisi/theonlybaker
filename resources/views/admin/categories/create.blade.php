
@extends('layouts.admin')

@section('title', 'Add Category | The Only Baker')

@section('content')
<div style="max-width:1000px;margin:0 auto;">

    <div style="margin-bottom:25px;">
        <a href="{{ route('admin.categories.index') }}"
           style="color:#593728;text-decoration:none;">
            ← Back to Categories
        </a>

        <h1 style="color:#593728;margin:15px 0 5px;">
            Add New Category
        </h1>

        <p style="color:#76665b;">
            Create a new product category.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        @include('admin.categories._form')
    </form>

</div>
@endsection
