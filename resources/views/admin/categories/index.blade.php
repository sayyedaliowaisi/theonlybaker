
@extends('layouts.admin')

@section('title', 'Category Management | The Only Baker')

@push('styles')
<style>
.tob-cat-page { max-width: 1200px; margin: 0 auto; }
.tob-cat-heading {
    display: flex; justify-content: space-between; align-items: center;
    gap: 20px; flex-wrap: wrap; margin-bottom: 25px;
}
.tob-cat-heading h1 { color: #593728; margin: 0 0 8px; font-size: 28px; }
.tob-cat-heading p { color: #76665b; margin: 0; }
.tob-cat-btn {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 11px 20px; border-radius: 9px; text-decoration: none;
    border: none; font-weight: 600; cursor: pointer;
}
.tob-cat-primary { background: #593728; color: #fff; }
.tob-cat-primary:hover { background: #754b36; color: #fff; }
.tob-cat-outline { border: 1px solid #d7c2af; color: #593728; background: #fff; }
.tob-cat-panel {
    background: #fff; border: 1px solid #ead8c1;
    border-radius: 14px; overflow: hidden;
}
.tob-cat-scroll { overflow-x: auto; }
.tob-cat-table { width: 100%; border-collapse: collapse; text-align: left; }
.tob-cat-table th, .tob-cat-table td {
    padding: 17px 20px; border-bottom: 1px solid #f0e6dc;
}
.tob-cat-table th { background: #fff8ef; color: #593728; font-size: 13px; }
.tob-cat-table td { color: #514139; }
.tob-cat-table tr:last-child td { border-bottom: 0; }
.tob-cat-slug { display: block; color: #988477; font-size: 12px; margin-top: 5px; }
.tob-cat-status {
    display: inline-block; padding: 5px 11px;
    border-radius: 20px; font-size: 12px; font-weight: 700;
}
.tob-cat-active { background: #e6f5eb; color: #237747; }
.tob-cat-inactive { background: #fff0ed; color: #a44b39; }
.tob-cat-empty { padding: 35px; text-align: center; }
</style>
@endpush

@section('content')
<div class="tob-cat-page">

    <div class="tob-cat-heading">
        <div>
            <h1>Category Management</h1>
            <p>Manage product categories for The Only Baker.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="tob-cat-btn tob-cat-primary">
            + Add New Category
        </a>
    </div>

    <div class="tob-cat-panel">
        <div class="tob-cat-scroll">
            <table class="tob-cat-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Display Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                <span class="tob-cat-slug">
                                    {{ $category->slug }}
                                </span>
                            </td>

                            <td>{{ $category->products_count }}</td>

                            <td>{{ $category->sort_order }}</td>

                            <td>
                                <span class="tob-cat-status {{ $category->is_active ? 'tob-cat-active' : 'tob-cat-inactive' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="tob-cat-btn tob-cat-outline">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="tob-cat-empty">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
