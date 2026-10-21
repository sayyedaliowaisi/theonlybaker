
@php
    $editing = isset($category);
@endphp

<style>
.tob-cat-form-panel {
    max-width: 800px; background: #fff; border: 1px solid #ead8c1;
    padding: 28px; border-radius: 14px;
}
.tob-cat-field { margin-bottom: 22px; }
.tob-cat-field label {
    display: block; font-weight: 600; color: #593728; margin-bottom: 9px;
}
.tob-cat-field input:not([type="checkbox"]),
.tob-cat-field textarea {
    width: 100%; padding: 12px 14px; border: 1px solid #ddcabb;
    border-radius: 9px; font: inherit; box-sizing: border-box;
    background: #fff; color: #35251d;
}
.tob-cat-field input:focus,
.tob-cat-field textarea:focus {
    outline: 2px solid #c9a989; outline-offset: 1px;
}
.tob-cat-help { display: block; margin-top: 7px; color: #89786b; font-size: 12px; }
.tob-cat-error { color: #b23a32; font-size: 12px; display: block; margin-top: 6px; }
.tob-cat-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 25px; }
.tob-cat-btn {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 11px 20px; border-radius: 9px; text-decoration: none;
    font: inherit; font-weight: 600; cursor: pointer;
}
.tob-cat-primary { background: #593728; color: white; border: 1px solid #593728; }
.tob-cat-outline { background: white; color: #593728; border: 1px solid #d7c2af; }
.tob-cat-checkbox { display: flex; align-items: center; gap: 10px; color: #593728; }
</style>

<div class="tob-cat-form-panel">

    <div class="tob-cat-field">
        <label for="category-name">Category Name *</label>
        <input
            type="text"
            id="category-name"
            name="name"
            value="{{ old('name', $category->name ?? '') }}"
            maxlength="100"
            required
        >
        @error('name')
            <small class="tob-cat-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="tob-cat-field">
        <label for="category-slug">Category Slug</label>
        <input
            type="text"
            id="category-slug"
            name="slug"
            value="{{ old('slug', $category->slug ?? '') }}"
            maxlength="100"
            placeholder="e.g. festive-cookies"
            {{ $editing ? 'readonly' : '' }}
        >
        <small class="tob-cat-help">
            Leave blank to generate from name.
            @if($editing)
                Existing slugs cannot be changed.
            @endif
        </small>
        @error('slug')
            <small class="tob-cat-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="tob-cat-field">
        <label for="category-description">Description</label>
        <textarea
            id="category-description"
            name="description"
            rows="4"
            maxlength="2000"
        >{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <small class="tob-cat-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="tob-cat-field">
        <label for="category-order">Display Order *</label>
        <input
            type="number"
            id="category-order"
            name="sort_order"
            min="0"
            value="{{ old('sort_order', $category->sort_order ?? 0) }}"
            required
        >
        @error('sort_order')
            <small class="tob-cat-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="tob-cat-field">
        <label class="tob-cat-checkbox">
            <input type="hidden" name="is_active" value="0">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked((bool) old('is_active', $category->is_active ?? true))
            >
            Active Category
        </label>
    </div>

    <div class="tob-cat-actions">
        <button type="submit" class="tob-cat-btn tob-cat-primary">
            {{ $editing ? 'Update Category' : 'Create Category' }}
        </button>

        <a href="{{ route('admin.categories.index') }}"
           class="tob-cat-btn tob-cat-outline">
            Cancel
        </a>
    </div>

</div>
